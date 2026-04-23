<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProductCategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ProductCategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:ver-categorias-productos')->only('index');
        $this->middleware('permission:crear-categorias-productos')->only(['create', 'store']);
        $this->middleware('permission:editar-categorias-productos')->only(['edit', 'update']);
        $this->middleware('permission:eliminar-categorias-productos')->only('destroy');
    }

    public function index(): View
    {
        $categories = ProductCategory::query()
            ->withCount('products')
            ->orderBy('name')
            ->paginate(10);

        $stats = [
            'categories' => ProductCategory::count(),
            'active' => ProductCategory::where('is_active', true)->count(),
            'products' => \App\Models\Product::count(),
        ];

        return view('admin.product-categories.index', compact('categories', 'stats'));
    }

    public function create(): View
    {
        return view('admin.product-categories.create', [
            'category' => new ProductCategory(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:120', 'unique:product_categories,name'],
            'code' => ['nullable', 'string', 'max:120'],
            'description' => ['nullable', 'string', 'max:1000'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $code = $this->resolveCode($validated['code'] ?? null, $validated['name']);

        if (ProductCategory::where('code', $code)->exists()) {
            return back()
                ->withErrors(['code' => 'La clave interna ya existe para otra categoria.'])
                ->withInput();
        }

        ProductCategory::create([
            'name' => $validated['name'],
            'code' => $code,
            'description' => $validated['description'] ?? null,
            'is_active' => $request->boolean('is_active', true),
        ]);

        return redirect()
            ->route('admin.product-categories.index')
            ->with('success', 'Categoria creada correctamente.');
    }

    public function edit(ProductCategory $productCategory): View
    {
        return view('admin.product-categories.edit', [
            'category' => $productCategory,
        ]);
    }

    public function update(Request $request, ProductCategory $productCategory): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:120', 'unique:product_categories,name,' . $productCategory->id],
            'code' => ['nullable', 'string', 'max:120'],
            'description' => ['nullable', 'string', 'max:1000'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $code = $this->resolveCode($validated['code'] ?? null, $validated['name']);

        if (ProductCategory::where('code', $code)->where('id', '!=', $productCategory->id)->exists()) {
            return back()
                ->withErrors(['code' => 'La clave interna ya existe para otra categoria.'])
                ->withInput();
        }

        $productCategory->update([
            'name' => $validated['name'],
            'code' => $code,
            'description' => $validated['description'] ?? null,
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()
            ->route('admin.product-categories.index')
            ->with('success', 'Categoria actualizada correctamente.');
    }

    public function destroy(ProductCategory $productCategory): RedirectResponse
    {
        if ($productCategory->products()->exists()) {
            return back()->with('error', 'No puedes eliminar una categoria que tiene productos asociados.');
        }

        $productCategory->delete();

        return redirect()
            ->route('admin.product-categories.index')
            ->with('success', 'Categoria eliminada correctamente.');
    }

    private function resolveCode(?string $code, string $name): string
    {
        return Str::slug(filled($code) ? $code : $name);
    }
}
