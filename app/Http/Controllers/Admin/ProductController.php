<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProductAttachment;
use App\Models\Product;
use App\Models\ProductBrand;
use App\Models\ProductCategory;
use App\Models\ProductImage;
use App\Models\ProductParameter;
use App\Models\ProductParameterValue;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:ver-productos-biomedicos')->only('index');
        $this->middleware('permission:crear-productos-biomedicos')->only(['create', 'store']);
        $this->middleware('permission:editar-productos-biomedicos')->only(['edit', 'update']);
        $this->middleware('permission:eliminar-productos-biomedicos')->only('destroy');
    }

    public function index(): View
    {
        $products = Product::query()
            ->with(['category', 'productBrand'])
            ->withCount(['specifications', 'images', 'attachments', 'parameterValues'])
            ->orderBy('name')
            ->paginate(10);

        $stats = [
            'products' => Product::count(),
            'active' => Product::where('is_active', true)->count(),
            'categories' => ProductCategory::count(),
            'brands' => ProductBrand::count(),
            'specifications' => \App\Models\ProductSpecification::count(),
            'parameters' => ProductParameter::count(),
            'parameter_values' => ProductParameterValue::count(),
            'images' => ProductImage::count(),
            'attachments' => ProductAttachment::count(),
        ];

        return view('admin.products.index', compact('products', 'stats'));
    }

    public function create(): View
    {
        $categories = ProductCategory::query()
            ->orderBy('name')
            ->get();

        return view('admin.products.create', [
            'product' => new Product(),
            'categories' => $categories,
            'brands' => $this->availableBrands(),
            'parameters' => $this->availableParameters(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validatePayload($request);
        $payload = $this->productPayload($request, $validated);

        DB::transaction(function () use ($request, $payload, $validated): void {
            $product = Product::create($payload);
            $this->syncSpecifications($product, $validated['specifications'] ?? []);
            $this->syncParameterValues($product, $validated['parameter_values'] ?? []);
            $this->syncMedia($request, $product);
        });

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Producto biomedico creado correctamente.');
    }

    public function edit(Product $product): View
    {
        $product->load(['productBrand', 'specifications', 'images', 'attachments', 'parameterValues.parameter', 'parameterValues.unit']);

        $categories = ProductCategory::query()
            ->orderBy('name')
            ->get();

        return view('admin.products.edit', [
            'product' => $product,
            'categories' => $categories,
            'brands' => $this->availableBrands($product),
            'parameters' => $this->availableParameters($product),
        ]);
    }

    public function update(Request $request, Product $product): RedirectResponse
    {
        $validated = $this->validatePayload($request, $product);
        $payload = $this->productPayload($request, $validated);

        DB::transaction(function () use ($request, $product, $payload, $validated): void {
            $product->update($payload);
            $this->syncSpecifications($product, $validated['specifications'] ?? []);
            $this->syncParameterValues($product, $validated['parameter_values'] ?? []);
            $this->syncMedia($request, $product);
        });

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Producto biomedico actualizado correctamente.');
    }

    public function destroy(Product $product): RedirectResponse
    {
        $product->delete();

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Producto biomedico eliminado correctamente.');
    }

    private function validatePayload(Request $request, ?Product $product = null): array
    {
        $validator = Validator::make($request->all(), [
            'category_id' => ['required', Rule::exists('product_categories', 'id')],
            'name' => ['required', 'string', 'max:160'],
            'code' => ['nullable', 'string', 'max:120'],
            'sku' => [
                'nullable',
                'string',
                'max:120',
                Rule::unique('products', 'sku')->ignore($product?->id),
            ],
            'stock_actual' => ['nullable', 'integer', 'min:0'],
            'brand_id' => ['nullable', 'integer', Rule::exists('product_brands', 'id')],
            'model' => ['nullable', 'string', 'max:120'],
            'manufacturer' => ['nullable', 'string', 'max:120'],
            'short_description' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:4000'],
            'is_active' => ['nullable', 'boolean'],
            'remove_featured_image' => ['nullable', 'boolean'],
            'featured_image' => ['nullable', 'image', 'max:5120'],
            'gallery_images' => ['nullable', 'array'],
            'gallery_images.*' => ['nullable', 'image', 'max:5120'],
            'remove_image_ids' => ['nullable', 'array'],
            'remove_image_ids.*' => ['nullable', Rule::exists('product_images', 'id')],
            'attachments_files' => ['nullable', 'array'],
            'attachments_files.*' => ['nullable', 'file', 'max:10240'],
            'remove_attachment_ids' => ['nullable', 'array'],
            'remove_attachment_ids.*' => ['nullable', Rule::exists('product_attachments', 'id')],
            'specifications' => ['nullable', 'array'],
            'specifications.*.label' => ['nullable', 'string', 'max:120'],
            'specifications.*.value' => ['nullable', 'string', 'max:255'],
            'specifications.*.unit' => ['nullable', 'string', 'max:60'],
            'specifications.*.sort_order' => ['nullable', 'integer', 'min:0'],
            'parameter_values' => ['nullable', 'array'],
            'parameter_values.*.product_parameter_id' => ['nullable', 'integer', Rule::exists('product_parameters', 'id')],
            'parameter_values.*.product_parameter_unit_id' => ['nullable', 'integer', Rule::exists('product_parameter_units', 'id')],
            'parameter_values.*.value' => ['nullable', 'string', 'max:255'],
            'parameter_values.*.sort_order' => ['nullable', 'integer', 'min:0'],
        ]);

        $validator->after(function ($validator) use ($request): void {
            $rows = collect($request->input('parameter_values', []));
            $selectedParameterIds = $rows
                ->pluck('product_parameter_id')
                ->filter(fn ($value) => filled($value))
                ->map(fn ($value) => (int) $value)
                ->unique()
                ->values();

            $parameters = ProductParameter::query()
                ->with('units:id,product_parameter_id')
                ->whereIn('id', $selectedParameterIds)
                ->get()
                ->keyBy('id');

            $usedParameters = [];

            foreach ($rows as $index => $row) {
                $parameterId = filled($row['product_parameter_id'] ?? null) ? (int) $row['product_parameter_id'] : null;
                $unitId = filled($row['product_parameter_unit_id'] ?? null) ? (int) $row['product_parameter_unit_id'] : null;
                $value = trim((string) ($row['value'] ?? ''));

                if (! $parameterId && ! $unitId && ! filled($value)) {
                    continue;
                }

                if (! $parameterId) {
                    $validator->errors()->add("parameter_values.$index.product_parameter_id", 'Selecciona un parametro para esta fila.');
                    continue;
                }

                if (in_array($parameterId, $usedParameters, true)) {
                    $validator->errors()->add("parameter_values.$index.product_parameter_id", 'Cada parametro solo puede asignarse una vez por producto.');
                } else {
                    $usedParameters[] = $parameterId;
                }

                if (! filled($value)) {
                    $validator->errors()->add("parameter_values.$index.value", 'Ingresa un valor para el parametro seleccionado.');
                }

                $parameter = $parameters->get($parameterId);

                if (! $parameter) {
                    continue;
                }

                if ($parameter->value_type === 'number' && filled($value) && $this->normalizeNumericValue($value) === null) {
                    $validator->errors()->add("parameter_values.$index.value", 'Este parametro requiere un valor numerico.');
                }

                if ($unitId && ! $parameter->units->contains('id', $unitId)) {
                    $validator->errors()->add("parameter_values.$index.product_parameter_unit_id", 'La unidad seleccionada no pertenece al parametro indicado.');
                }
            }
        });

        return $validator->validate();
    }

    private function productPayload(Request $request, array $validated): array
    {
        $code = Str::slug(filled($validated['code'] ?? null) ? $validated['code'] : $validated['name']);
        $brandId = filled($validated['brand_id'] ?? null) ? (int) $validated['brand_id'] : null;
        $productBrand = $brandId ? ProductBrand::query()->find($brandId) : null;

        $query = Product::where('code', $code);

        if ($request->route('product')) {
            $query->where('id', '!=', $request->route('product')->id);
        }

        if ($query->exists()) {
            throw ValidationException::withMessages([
                'code' => 'La clave interna ya existe para otro producto.',
            ]);
        }

        return [
            'category_id' => $validated['category_id'],
            'brand_id' => $productBrand?->id,
            'name' => $validated['name'],
            'code' => $code,
            'sku' => $validated['sku'] ?? null,
            'stock_actual' => (int) ($validated['stock_actual'] ?? 0),
            'brand' => $productBrand?->name,
            'model' => $validated['model'] ?? null,
            'manufacturer' => $validated['manufacturer'] ?? null,
            'short_description' => $validated['short_description'] ?? null,
            'description' => $validated['description'] ?? null,
            'is_active' => $request->boolean('is_active', true),
        ];
    }

    private function syncMedia(Request $request, Product $product): void
    {
        if ($request->boolean('remove_featured_image')) {
            $this->deleteFeaturedImage($product);
        }

        if ($request->hasFile('featured_image')) {
            $this->storeFeaturedImage($request, $product);
        }

        $removeImageIds = collect($request->input('remove_image_ids', []))
            ->filter()
            ->map(fn ($value) => (int) $value);

        if ($removeImageIds->isNotEmpty()) {
            $product->images()
                ->whereIn('id', $removeImageIds)
                ->get()
                ->each
                ->delete();
        }

        if ($request->hasFile('gallery_images')) {
            $startingOrder = ((int) $product->images()->max('sort_order')) + 10;

            foreach ($request->file('gallery_images') as $index => $file) {
                if (! $file) {
                    continue;
                }

                $path = $file->store('products/gallery', 'local');

                $product->images()->create([
                    'disk' => 'local',
                    'path' => $path,
                    'original_name' => $file->getClientOriginalName(),
                    'mime_type' => $file->getClientMimeType(),
                    'size' => $file->getSize(),
                    'sort_order' => $startingOrder + ($index * 10),
                ]);
            }
        }

        $removeAttachmentIds = collect($request->input('remove_attachment_ids', []))
            ->filter()
            ->map(fn ($value) => (int) $value);

        if ($removeAttachmentIds->isNotEmpty()) {
            $product->attachments()
                ->whereIn('id', $removeAttachmentIds)
                ->get()
                ->each
                ->delete();
        }

        if ($request->hasFile('attachments_files')) {
            foreach ($request->file('attachments_files') as $file) {
                if (! $file) {
                    continue;
                }

                $path = $file->store('products/attachments', 'local');

                $product->attachments()->create([
                    'disk' => 'local',
                    'path' => $path,
                    'original_name' => $file->getClientOriginalName(),
                    'mime_type' => $file->getClientMimeType(),
                    'size' => $file->getSize(),
                ]);
            }
        }
    }

    private function storeFeaturedImage(Request $request, Product $product): void
    {
        $file = $request->file('featured_image');

        if (! $file) {
            return;
        }

        $this->deleteFeaturedImage($product);

        $path = $file->store('products/featured', 'local');

        $product->forceFill([
            'featured_image_disk' => 'local',
            'featured_image_path' => $path,
            'featured_image_name' => $file->getClientOriginalName(),
            'featured_image_mime_type' => $file->getClientMimeType(),
            'featured_image_size' => $file->getSize(),
        ])->save();
    }

    private function deleteFeaturedImage(Product $product): void
    {
        if (! $product->featured_image_path) {
            return;
        }

        Storage::disk($product->featured_image_disk ?: 'local')->delete($product->featured_image_path);

        $product->forceFill([
            'featured_image_disk' => null,
            'featured_image_path' => null,
            'featured_image_name' => null,
            'featured_image_mime_type' => null,
            'featured_image_size' => null,
        ])->save();
    }

    private function syncSpecifications(Product $product, array $rows): void
    {
        $product->specifications()->delete();

        $cleanRows = collect($rows)
            ->map(function (array $row, int $index): array {
                return [
                    'label' => trim((string) ($row['label'] ?? '')),
                    'value' => trim((string) ($row['value'] ?? '')),
                    'unit' => trim((string) ($row['unit'] ?? '')),
                    'sort_order' => isset($row['sort_order']) && $row['sort_order'] !== ''
                        ? (int) $row['sort_order']
                        : (($index + 1) * 10),
                ];
            })
            ->filter(fn (array $row): bool => filled($row['label']) && filled($row['value']))
            ->values();

        foreach ($cleanRows as $row) {
            $product->specifications()->create($row);
        }
    }

    private function syncParameterValues(Product $product, array $rows): void
    {
        $product->parameterValues()->delete();

        $parameterMap = ProductParameter::query()
            ->whereIn('id', collect($rows)->pluck('product_parameter_id')->filter()->map(fn ($value) => (int) $value)->unique())
            ->get()
            ->keyBy('id');

        $cleanRows = collect($rows)
            ->map(function (array $row, int $index) use ($parameterMap): ?array {
                $parameterId = filled($row['product_parameter_id'] ?? null) ? (int) $row['product_parameter_id'] : null;
                $value = trim((string) ($row['value'] ?? ''));

                if (! $parameterId || ! filled($value)) {
                    return null;
                }

                $parameter = $parameterMap->get($parameterId);

                if (! $parameter) {
                    return null;
                }

                return [
                    'product_parameter_id' => $parameterId,
                    'product_parameter_unit_id' => filled($row['product_parameter_unit_id'] ?? null)
                        ? (int) $row['product_parameter_unit_id']
                        : null,
                    'value_text' => $value,
                    'value_number' => $parameter->value_type === 'number'
                        ? $this->normalizeNumericValue($value)
                        : null,
                    'sort_order' => isset($row['sort_order']) && $row['sort_order'] !== ''
                        ? (int) $row['sort_order']
                        : (($index + 1) * 10),
                ];
            })
            ->filter()
            ->values();

        foreach ($cleanRows as $row) {
            $product->parameterValues()->create($row);
        }
    }

    private function availableParameters(?Product $product = null): Collection
    {
        $assignedIds = $product?->exists
            ? $product->parameterValues()->pluck('product_parameter_id')
            : collect();

        return ProductParameter::query()
            ->with('units')
            ->where(function ($query) use ($assignedIds): void {
                $query->where('is_active', true);

                if ($assignedIds->isNotEmpty()) {
                    $query->orWhereIn('id', $assignedIds);
                }
            })
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();
    }

    private function availableBrands(?Product $product = null): Collection
    {
        $assignedBrandId = $product?->brand_id;

        return ProductBrand::query()
            ->where(function ($query) use ($assignedBrandId): void {
                $query->where('is_active', true);

                if ($assignedBrandId) {
                    $query->orWhere('id', $assignedBrandId);
                }
            })
            ->orderBy('name')
            ->get();
    }

    private function normalizeNumericValue(string $value): ?string
    {
        $normalized = preg_replace('/\s+/', '', trim($value)) ?? '';

        if ($normalized === '') {
            return null;
        }

        $lastComma = strrpos($normalized, ',');
        $lastDot = strrpos($normalized, '.');

        if ($lastComma !== false && $lastDot !== false) {
            if ($lastComma > $lastDot) {
                $normalized = str_replace('.', '', $normalized);
                $normalized = str_replace(',', '.', $normalized);
            } else {
                $normalized = str_replace(',', '', $normalized);
            }
        } elseif ($lastComma !== false) {
            $normalized = str_replace('.', '', $normalized);
            $normalized = str_replace(',', '.', $normalized);
        } else {
            $normalized = str_replace(',', '', $normalized);
        }

        return is_numeric($normalized) ? $normalized : null;
    }
}
