<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProductBrand;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ProductBrandController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:ver-marcas-productos')->only(['index', 'logo']);
        $this->middleware('permission:crear-marcas-productos')->only(['create', 'store']);
        $this->middleware('permission:editar-marcas-productos')->only(['edit', 'update']);
        $this->middleware('permission:eliminar-marcas-productos')->only('destroy');
    }

    public function index(): View
    {
        $brands = ProductBrand::query()
            ->withCount('products')
            ->orderBy('name')
            ->paginate(10);

        $stats = [
            'brands' => ProductBrand::count(),
            'active' => ProductBrand::where('is_active', true)->count(),
            'with_logo' => ProductBrand::whereNotNull('logo_path')->count(),
            'products' => \App\Models\Product::whereNotNull('brand_id')->count(),
        ];

        return view('admin.product-brands.index', compact('brands', 'stats'));
    }

    public function create(): View
    {
        return view('admin.product-brands.create', [
            'productBrand' => new ProductBrand(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validatePayload($request);
        $code = $this->resolveCode($validated['code'] ?? null, $validated['name']);

        if (ProductBrand::where('code', $code)->exists()) {
            return back()
                ->withErrors(['code' => 'La clave interna ya existe para otra marca.'])
                ->withInput();
        }

        $productBrand = ProductBrand::create([
            'name' => $validated['name'],
            'code' => $code,
            'description' => $validated['description'] ?? null,
            'is_active' => $request->boolean('is_active', true),
        ]);

        $this->syncLogo($request, $productBrand);

        return redirect()
            ->route('admin.product-brands.index')
            ->with('success', 'Marca creada correctamente.');
    }

    public function edit(ProductBrand $productBrand): View
    {
        return view('admin.product-brands.edit', compact('productBrand'));
    }

    public function update(Request $request, ProductBrand $productBrand): RedirectResponse
    {
        $validated = $this->validatePayload($request, $productBrand);
        $code = $this->resolveCode($validated['code'] ?? null, $validated['name']);

        if (ProductBrand::where('code', $code)->where('id', '!=', $productBrand->id)->exists()) {
            return back()
                ->withErrors(['code' => 'La clave interna ya existe para otra marca.'])
                ->withInput();
        }

        $previousName = $productBrand->name;

        $productBrand->update([
            'name' => $validated['name'],
            'code' => $code,
            'description' => $validated['description'] ?? null,
            'is_active' => $request->boolean('is_active'),
        ]);

        if ($previousName !== $productBrand->name) {
            $productBrand->products()->update(['brand' => $productBrand->name]);
        }

        $this->syncLogo($request, $productBrand);

        return redirect()
            ->route('admin.product-brands.index')
            ->with('success', 'Marca actualizada correctamente.');
    }

    public function destroy(ProductBrand $productBrand): RedirectResponse
    {
        if ($productBrand->products()->exists()) {
            return back()->with('error', 'No puedes eliminar una marca que tiene productos asociados.');
        }

        $productBrand->delete();

        return redirect()
            ->route('admin.product-brands.index')
            ->with('success', 'Marca eliminada correctamente.');
    }

    public function logo(ProductBrand $productBrand): StreamedResponse
    {
        abort_unless($productBrand->logo_path, Response::HTTP_NOT_FOUND);

        return Storage::disk($productBrand->logo_disk ?: 'local')->response(
            $productBrand->logo_path,
            $productBrand->logo_name ?: basename($productBrand->logo_path),
            [
                'Content-Type' => $productBrand->logo_mime_type ?: 'application/octet-stream',
                'Cache-Control' => 'public, max-age=86400',
            ],
        );
    }

    private function validatePayload(Request $request, ?ProductBrand $productBrand = null): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:120', 'unique:product_brands,name,' . $productBrand?->id],
            'code' => ['nullable', 'string', 'max:120'],
            'description' => ['nullable', 'string', 'max:1000'],
            'is_active' => ['nullable', 'boolean'],
            'remove_logo' => ['nullable', 'boolean'],
            'logo' => ['nullable', 'image', 'max:5120'],
        ]);
    }

    private function syncLogo(Request $request, ProductBrand $productBrand): void
    {
        if ($request->boolean('remove_logo')) {
            $this->deleteLogo($productBrand);
        }

        if ($request->hasFile('logo')) {
            $this->storeLogo($request, $productBrand);
        }
    }

    private function storeLogo(Request $request, ProductBrand $productBrand): void
    {
        $file = $request->file('logo');

        if (! $file) {
            return;
        }

        $this->deleteLogo($productBrand);

        $path = $file->store('brands/logos', 'local');

        $productBrand->forceFill([
            'logo_disk' => 'local',
            'logo_path' => $path,
            'logo_name' => $file->getClientOriginalName(),
            'logo_mime_type' => $file->getClientMimeType(),
            'logo_size' => $file->getSize(),
        ])->save();
    }

    private function deleteLogo(ProductBrand $productBrand): void
    {
        if ($productBrand->logo_path) {
            Storage::disk($productBrand->logo_disk ?: 'local')->delete($productBrand->logo_path);
        }

        $productBrand->forceFill([
            'logo_disk' => null,
            'logo_path' => null,
            'logo_name' => null,
            'logo_mime_type' => null,
            'logo_size' => null,
        ])->save();
    }

    private function resolveCode(?string $code, string $name): string
    {
        return Str::slug(filled($code) ? $code : $name);
    }
}
