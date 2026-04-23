@extends('layouts.admin')

@section('title', 'Productos biomedicos')
@section('page-kicker', 'Catalogo biomedico')
@section('page-title', 'Productos biomedicos')
@section('page-description', 'Administra el catalogo biomedico, sus marcas, descripciones, especificaciones tecnicas y clasificacion por categorias.')

@section('header-actions')
    <a href="{{ route('admin.products.create') }}" class="inline-flex items-center justify-center rounded-2xl bg-slate-950 px-4 py-3 text-sm font-semibold text-white shadow-lg shadow-slate-950/15 transition hover:bg-cyan-700">
        Nuevo producto
    </a>
@endsection

@section('content')
    <div class="space-y-6">
        @include('admin.products.tabs')

        <section class="grid gap-4 md:grid-cols-2 xl:grid-cols-4 2xl:grid-cols-8">
            <article class="app-panel rounded-[28px] p-5">
                <p class="text-sm font-medium text-slate-500">Productos</p>
                <p class="font-display mt-3 text-4xl font-semibold tracking-tight text-slate-950">{{ $stats['products'] }}</p>
                <p class="mt-2 text-sm leading-6 text-slate-600">Referencias registradas en el catalogo biomedico.</p>
            </article>

            <article class="app-panel rounded-[28px] p-5">
                <p class="text-sm font-medium text-slate-500">Activos</p>
                <p class="font-display mt-3 text-4xl font-semibold tracking-tight text-slate-950">{{ $stats['active'] }}</p>
                <p class="mt-2 text-sm leading-6 text-slate-600">Productos disponibles para operaciones y catalogos internos.</p>
            </article>

            <article class="app-panel rounded-[28px] p-5">
                <p class="text-sm font-medium text-slate-500">Categorias</p>
                <p class="font-display mt-3 text-4xl font-semibold tracking-tight text-slate-950">{{ $stats['categories'] }}</p>
                <p class="mt-2 text-sm leading-6 text-slate-600">Agrupaciones funcionales usadas por el catalogo.</p>
            </article>

            <article class="app-panel rounded-[28px] p-5">
                <p class="text-sm font-medium text-slate-500">Marcas</p>
                <p class="font-display mt-3 text-4xl font-semibold tracking-tight text-slate-950">{{ $stats['brands'] }}</p>
                <p class="mt-2 text-sm leading-6 text-slate-600">Catalogo de marcas reutilizable con logo y relacion directa al producto.</p>
            </article>

            <article class="app-panel rounded-[28px] p-5">
                <p class="text-sm font-medium text-slate-500">Especificaciones</p>
                <p class="font-display mt-3 text-4xl font-semibold tracking-tight text-slate-950">{{ $stats['specifications'] }}</p>
                <p class="mt-2 text-sm leading-6 text-slate-600">Datos tecnicos cargados en los formularios de producto.</p>
            </article>

            <article class="app-panel rounded-[28px] p-5">
                <p class="text-sm font-medium text-slate-500">Parametros</p>
                <p class="font-display mt-3 text-4xl font-semibold tracking-tight text-slate-950">{{ $stats['parameters'] }}</p>
                <p class="mt-2 text-sm leading-6 text-slate-600">Campos filtrables reutilizables con unidades normalizadas.</p>
            </article>

            <article class="app-panel rounded-[28px] p-5">
                <p class="text-sm font-medium text-slate-500">Imagenes</p>
                <p class="font-display mt-3 text-4xl font-semibold tracking-tight text-slate-950">{{ $stats['images'] }}</p>
                <p class="mt-2 text-sm leading-6 text-slate-600">Galeria visual asociada a los productos del catalogo.</p>
            </article>

            <article class="app-panel rounded-[28px] p-5">
                <p class="text-sm font-medium text-slate-500">Adjuntos</p>
                <p class="font-display mt-3 text-4xl font-semibold tracking-tight text-slate-950">{{ $stats['attachments'] }}</p>
                <p class="mt-2 text-sm leading-6 text-slate-600">Archivos tecnicos o comerciales anexados al producto.</p>
            </article>
        </section>

        <section class="grid gap-6 xl:grid-cols-[minmax(0,1.45fr)_360px]">
            <article class="app-panel rounded-[34px] p-6">
                <div class="flex flex-wrap items-center justify-between gap-3">
                    <div>
                        <p class="text-sm font-medium text-slate-500">Catalogo principal</p>
                        <h2 class="font-display mt-2 text-2xl font-semibold tracking-tight text-slate-950">Productos registrados</h2>
                    </div>

                    <span class="rounded-full border border-slate-200 bg-white/80 px-4 py-2 text-sm font-medium text-slate-600 shadow-sm">
                        {{ $products->total() }} registros
                    </span>
                </div>

                <div class="mt-6 overflow-hidden rounded-[26px] border border-slate-200">
                    @if ($products->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-slate-200 bg-white/80 text-sm">
                                <thead class="bg-slate-950/4 text-left text-xs font-semibold uppercase tracking-[0.24em] text-slate-500">
                                    <tr>
                                        <th class="px-5 py-4">Producto</th>
                                        <th class="px-5 py-4">Categoria</th>
                                        <th class="px-5 py-4">Marca / Modelo</th>
                                        <th class="px-5 py-4">Stock</th>
                                        <th class="px-5 py-4">Especificaciones</th>
                                        <th class="px-5 py-4">Estado</th>
                                        <th class="px-5 py-4 text-right">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-200">
                                    @foreach ($products as $product)
                                        <tr class="align-top">
                                            <td class="px-5 py-4">
                                                <div class="flex items-start gap-4">
                                                    @if ($product->hasFeaturedImage())
                                                        <img
                                                            src="{{ route('admin.products.featured-image', $product) }}"
                                                            alt="{{ $product->name }}"
                                                            class="h-18 w-18 rounded-[18px] border border-slate-200 object-cover"
                                                        >
                                                    @else
                                                        <div class="flex h-18 w-18 items-center justify-center rounded-[18px] border border-dashed border-slate-300 bg-slate-50 text-[11px] font-semibold uppercase tracking-[0.18em] text-slate-400">
                                                            Sin foto
                                                        </div>
                                                    @endif

                                                    <div class="min-w-0">
                                                        <p class="font-semibold text-slate-900">{{ $product->name }}</p>
                                                        <p class="mt-1 text-xs uppercase tracking-[0.2em] text-slate-400">{{ $product->code }}</p>
                                                        @if ($product->short_description)
                                                            <p class="mt-2 text-sm leading-6 text-slate-600">{{ $product->short_description }}</p>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-5 py-4 text-slate-600">{{ $product->category?->name }}</td>
                                            <td class="px-5 py-4 text-slate-600">
                                                <div class="flex items-center gap-3">
                                                    @if ($product->productBrand?->hasLogo())
                                                        <img
                                                            src="{{ route('admin.product-brands.logo', $product->productBrand) }}"
                                                            alt="{{ $product->productBrand->name }}"
                                                            class="h-12 w-12 rounded-[14px] border border-slate-200 bg-white object-contain p-2"
                                                        >
                                                    @endif

                                                    <div class="min-w-0">
                                                        <p>{{ $product->productBrand?->name ?? $product->brand ?: 'Sin marca' }}</p>
                                                        <p class="mt-1 text-xs uppercase tracking-[0.18em] text-slate-400">{{ $product->model ?: 'Sin modelo' }}</p>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-5 py-4">
                                                <span class="rounded-full px-3 py-1 text-xs font-semibold {{ $product->stock_actual > 0 ? 'bg-cyan-50 text-cyan-700' : 'bg-amber-50 text-amber-700' }}">
                                                    {{ number_format($product->stock_actual, 0, ',', '.') }} unidades
                                                </span>
                                            </td>
                                            <td class="px-5 py-4 text-slate-600">
                                                <p>{{ $product->specifications_count }} tecnicas</p>
                                                <p class="mt-1 text-xs uppercase tracking-[0.18em] text-slate-400">{{ $product->parameter_values_count }} parametros / {{ $product->images_count }} imagenes / {{ $product->attachments_count }} archivos</p>
                                            </td>
                                            <td class="px-5 py-4">
                                                <span class="rounded-full px-3 py-1 text-xs font-semibold {{ $product->is_active ? 'bg-emerald-50 text-emerald-700' : 'bg-slate-100 text-slate-600' }}">
                                                    {{ $product->is_active ? 'Activo' : 'Inactivo' }}
                                                </span>
                                            </td>
                                            <td class="px-5 py-4">
                                                <div class="flex justify-end gap-2">
                                                    <a href="{{ route('admin.products.edit', $product) }}" class="rounded-2xl border border-slate-200 bg-white px-3 py-2 text-xs font-semibold uppercase tracking-[0.18em] text-slate-600 transition hover:border-cyan-200 hover:text-slate-900">
                                                        Editar
                                                    </a>

                                                    <form action="{{ route('admin.products.destroy', $product) }}" method="POST" onsubmit="return confirm('Se eliminara el producto seleccionado.');">
                                                        @csrf
                                                        @method('DELETE')

                                                        <button type="submit" class="rounded-2xl border border-rose-200 bg-rose-50 px-3 py-2 text-xs font-semibold uppercase tracking-[0.18em] text-rose-700 transition hover:bg-rose-100">
                                                            Eliminar
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="px-6 py-12 text-center">
                            <p class="font-display text-2xl font-semibold text-slate-900">Todavia no hay productos cargados</p>
                            <p class="mx-auto mt-3 max-w-xl text-sm leading-6 text-slate-600">Crea categorias y despues registra productos con descripciones y especificaciones tecnicas.</p>
                            <a href="{{ route('admin.products.create') }}" class="mt-6 inline-flex items-center justify-center rounded-2xl bg-slate-950 px-4 py-3 text-sm font-semibold text-white transition hover:bg-cyan-700">
                                Crear producto
                            </a>
                        </div>
                    @endif
                </div>

                @if ($products->hasPages())
                    <div class="mt-6">
                        {{ $products->links() }}
                    </div>
                @endif
            </article>

            <aside class="space-y-6">
                <article class="app-panel rounded-[34px] p-6">
                    <p class="text-sm font-medium text-slate-500">Contenido del modulo</p>
                    <h2 class="font-display mt-2 text-2xl font-semibold tracking-tight text-slate-950">Ficha de producto</h2>
                    <p class="mt-4 text-sm leading-7 text-slate-600">
                        Cada producto concentra categoria, descripcion corta, descripcion ampliada, especificaciones libres y parametros normalizados listos para filtros futuros.
                    </p>
                </article>

                <article class="app-panel rounded-[34px] p-6">
                    <p class="text-sm font-medium text-slate-500">Dependencia</p>
                    <a href="{{ route('admin.product-categories.index') }}" class="mt-4 block rounded-[24px] border border-slate-200 bg-white/85 p-4 transition hover:border-cyan-200">
                        <p class="text-sm font-semibold text-slate-900">Administrar categorias</p>
                        <p class="mt-2 text-sm leading-6 text-slate-600">Los productos requieren una categoria activa para poder registrarse.</p>
                    </a>
                </article>

                <article class="app-panel rounded-[34px] p-6">
                    <p class="text-sm font-medium text-slate-500">Marcas</p>
                    <a href="{{ route('admin.product-brands.index') }}" class="mt-4 block rounded-[24px] border border-slate-200 bg-white/85 p-4 transition hover:border-cyan-200">
                        <p class="text-sm font-semibold text-slate-900">Administrar marcas</p>
                        <p class="mt-2 text-sm leading-6 text-slate-600">Centraliza logos y nombres de marca para seleccionar productos de forma consistente.</p>
                    </a>
                </article>

                <article class="app-panel rounded-[34px] p-6">
                    <p class="text-sm font-medium text-slate-500">Parametros</p>
                    <a href="{{ route('admin.product-parameters.index') }}" class="mt-4 block rounded-[24px] border border-slate-200 bg-white/85 p-4 transition hover:border-cyan-200">
                        <p class="text-sm font-semibold text-slate-900">Administrar parametros filtrables</p>
                        <p class="mt-2 text-sm leading-6 text-slate-600">Define campos reutilizables como peso, potencia, rango o autonomia con sus unidades.</p>
                    </a>
                </article>
            </aside>
        </section>
    </div>
@endsection
