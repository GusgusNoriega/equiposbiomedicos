<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProductParameter;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ProductParameterController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:ver-parametros-productos')->only('index');
        $this->middleware('permission:crear-parametros-productos')->only(['create', 'store']);
        $this->middleware('permission:editar-parametros-productos')->only(['edit', 'update']);
        $this->middleware('permission:eliminar-parametros-productos')->only('destroy');
    }

    public function index(): View
    {
        $parameters = ProductParameter::query()
            ->withCount(['units', 'values'])
            ->with('units')
            ->orderBy('sort_order')
            ->orderBy('name')
            ->paginate(10);

        $stats = [
            'parameters' => ProductParameter::count(),
            'active' => ProductParameter::where('is_active', true)->count(),
            'units' => \App\Models\ProductParameterUnit::count(),
            'assigned' => \App\Models\ProductParameterValue::count(),
        ];

        return view('admin.product-parameters.index', compact('parameters', 'stats'));
    }

    public function create(): View
    {
        return view('admin.product-parameters.create', [
            'parameter' => new ProductParameter(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validatePayload($request);
        $code = $this->resolveCode($validated['code'] ?? null, $validated['name']);

        if (ProductParameter::where('code', $code)->exists()) {
            return back()
                ->withErrors(['code' => 'La clave interna ya existe para otro parametro.'])
                ->withInput();
        }

        $parameter = ProductParameter::create([
            'name' => $validated['name'],
            'code' => $code,
            'description' => $validated['description'] ?? null,
            'value_type' => $validated['value_type'],
            'is_filterable' => $request->boolean('is_filterable', true),
            'is_active' => $request->boolean('is_active', true),
            'sort_order' => $validated['sort_order'] ?? 0,
        ]);

        $this->syncUnits($parameter, $validated['units'] ?? []);

        return redirect()
            ->route('admin.product-parameters.index')
            ->with('success', 'Parametro creado correctamente.');
    }

    public function edit(ProductParameter $productParameter): View
    {
        $productParameter->load('units');

        return view('admin.product-parameters.edit', [
            'parameter' => $productParameter,
        ]);
    }

    public function update(Request $request, ProductParameter $productParameter): RedirectResponse
    {
        $validated = $this->validatePayload($request, $productParameter);
        $code = $this->resolveCode($validated['code'] ?? null, $validated['name']);

        if (ProductParameter::where('code', $code)->where('id', '!=', $productParameter->id)->exists()) {
            return back()
                ->withErrors(['code' => 'La clave interna ya existe para otro parametro.'])
                ->withInput();
        }

        $productParameter->update([
            'name' => $validated['name'],
            'code' => $code,
            'description' => $validated['description'] ?? null,
            'value_type' => $validated['value_type'],
            'is_filterable' => $request->boolean('is_filterable'),
            'is_active' => $request->boolean('is_active'),
            'sort_order' => $validated['sort_order'] ?? 0,
        ]);

        $this->syncUnits($productParameter, $validated['units'] ?? []);

        return redirect()
            ->route('admin.product-parameters.index')
            ->with('success', 'Parametro actualizado correctamente.');
    }

    public function destroy(ProductParameter $productParameter): RedirectResponse
    {
        if ($productParameter->values()->exists()) {
            return back()->with('error', 'No puedes eliminar un parametro que ya tiene valores asignados en productos.');
        }

        $productParameter->delete();

        return redirect()
            ->route('admin.product-parameters.index')
            ->with('success', 'Parametro eliminado correctamente.');
    }

    private function validatePayload(Request $request, ?ProductParameter $parameter = null): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:120', 'unique:product_parameters,name,' . $parameter?->id],
            'code' => ['nullable', 'string', 'max:120'],
            'description' => ['nullable', 'string', 'max:1000'],
            'value_type' => ['required', 'in:text,number'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_filterable' => ['nullable', 'boolean'],
            'is_active' => ['nullable', 'boolean'],
            'units' => ['nullable', 'array'],
            'units.*.id' => ['nullable', 'integer'],
            'units.*.name' => ['nullable', 'string', 'max:80'],
            'units.*.symbol' => ['nullable', 'string', 'max:30'],
            'units.*.code' => ['nullable', 'string', 'max:80'],
            'units.*.sort_order' => ['nullable', 'integer', 'min:0'],
        ]);
    }

    private function syncUnits(ProductParameter $parameter, array $rows): void
    {
        $currentIds = $parameter->units()->pluck('id')->all();
        $keptIds = [];

        $cleanRows = collect($rows)
            ->map(function (array $row, int $index): array {
                return [
                    'id' => filled($row['id'] ?? null) ? (int) $row['id'] : null,
                    'name' => trim((string) ($row['name'] ?? '')),
                    'symbol' => trim((string) ($row['symbol'] ?? '')),
                    'code' => trim((string) ($row['code'] ?? '')),
                    'sort_order' => isset($row['sort_order']) && $row['sort_order'] !== ''
                        ? (int) $row['sort_order']
                        : (($index + 1) * 10),
                ];
            })
            ->filter(fn (array $row): bool => filled($row['name']) || filled($row['symbol']))
            ->values();

        foreach ($cleanRows as $row) {
            $payload = [
                'name' => $row['name'] ?: $row['symbol'],
                'symbol' => $row['symbol'] ?: null,
                'code' => Str::slug(filled($row['code']) ? $row['code'] : ($row['symbol'] ?: $row['name'])),
                'sort_order' => $row['sort_order'],
            ];

            if ($row['id']) {
                $unit = $parameter->units()->whereKey($row['id'])->first();

                if ($unit) {
                    $unit->update($payload);
                    $keptIds[] = $unit->id;
                    continue;
                }
            }

            $unit = $parameter->units()->create($payload);
            $keptIds[] = $unit->id;
        }

        $removeIds = array_diff($currentIds, $keptIds);

        if (! empty($removeIds)) {
            \App\Models\ProductParameterValue::query()
                ->whereIn('product_parameter_unit_id', $removeIds)
                ->update(['product_parameter_unit_id' => null]);

            $parameter->units()->whereIn('id', $removeIds)->delete();
        }
    }

    private function resolveCode(?string $code, string $name): string
    {
        return Str::slug(filled($code) ? $code : $name);
    }
}
