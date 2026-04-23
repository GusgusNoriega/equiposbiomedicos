@extends('layouts.admin')

@section('title', 'Marcas de productos')
@section('page-kicker', 'Catalogo biomedico')
@section('page-title', 'Marcas de productos')
@section('page-description', 'Centraliza las marcas del catalogo, carga logos y deja la base lista para filtros por marca en productos.')

@section('header-actions')
    <a href="{{ route('admin.product-brands.create') }}" class="inline-flex items-center justify-center rounded-2xl bg-slate-950 px-4 py-3 text-sm font-semibold text-white shadow-lg shadow-slate-950/15 transition hover:bg-cyan-700">
        Nueva marca
    </a>
@endsection

@section('content')
    <div class="space-y-6">
        @include('admin.products.tabs')

        <section class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
            <article class="app-panel rounded-[28px] p-5">
                <p class="text-sm font-medium text-slate-500">Marcas</p>
                <p class="font-display mt-3 text-4xl font-semibold tracking-tight text-slate-950">{{ $stats['brands'] }}</p>
                <p class="mt-2 text-sm leading-6 text-slate-600">Catalogo total de marcas registradas.</p>
            </article>

            <article class="app-panel rounded-[28px] p-5">
                <p class="text-sm font-medium text-slate-500">Activas</p>
                <p class="font-display mt-3 text-4xl font-semibold tracking-tight text-slate-950">{{ $stats['active'] }}</p>
                <p class="mt-2 text-sm leading-6 text-slate-600">Marcas disponibles para nuevos productos.</p>
            </article>

            <article class="app-panel rounded-[28px] p-5">
                <p class="text-sm font-medium text-slate-500">Con logo</p>
                <p class="font-display mt-3 text-4xl font-semibold tracking-tight text-slate-950">{{ $stats['with_logo'] }}</p>
                <p class="mt-2 text-sm leading-6 text-slate-600">Marcas con identidad visual cargada.</p>
            </article>

            <article class="app-panel rounded-[28px] p-5">
                <p class="text-sm font-medium text-slate-500">Productos vinculados</p>
                <p class="font-display mt-3 text-4xl font-semibold tracking-tight text-slate-950">{{ $stats['products'] }}</p>
                <p class="mt-2 text-sm leading-6 text-slate-600">Productos que ya usan el catalogo de marcas.</p>
            </article>
        </section>

        <section class="grid gap-6 xl:grid-cols-[minmax(0,1.35fr)_360px]">
            <div class="space-y-4">
                @forelse ($brands as $brand)
                    <article class="app-panel rounded-[34px] p-6">
                        <div class="flex flex-wrap items-start justify-between gap-4">
                            <div class="flex min-w-0 items-start gap-4">
                                @if ($brand->hasLogo())
                                    <img
                                        src="{{ route('admin.product-brands.logo', $brand) }}"
                                        alt="{{ $brand->name }}"
                                        class="h-20 w-20 rounded-[22px] border border-slate-200 bg-white object-contain p-3"
                                    >
                                @else
                                    <div class="flex h-20 w-20 items-center justify-center rounded-[22px] border border-dashed border-slate-300 bg-slate-50 text-[11px] font-semibold uppercase tracking-[0.18em] text-slate-400">
                                        Sin logo
                                    </div>
                                @endif

                                <div class="min-w-0">
                                    <div class="flex flex-wrap items-center gap-2">
                                        <h2 class="font-display text-2xl font-semibold tracking-tight text-slate-950">{{ $brand->name }}</h2>
                                        <span class="rounded-full px-3 py-1 text-xs font-semibold {{ $brand->is_active ? 'bg-emerald-50 text-emerald-700' : 'bg-slate-100 text-slate-600' }}">
                                            {{ $brand->is_active ? 'Activa' : 'Inactiva' }}
                                        </span>
                                    </div>

                                    <p class="mt-2 text-sm uppercase tracking-[0.22em] text-slate-400">{{ $brand->code }}</p>
                                    <p class="mt-4 text-sm leading-7 text-slate-600">{{ $brand->description ?: 'Esta marca aun no tiene descripcion comercial o tecnica.' }}</p>
                                </div>
                            </div>

                            <div class="flex flex-wrap gap-2">
                                <a href="{{ route('admin.product-brands.edit', $brand) }}" class="rounded-2xl border border-slate-200 bg-white px-3 py-2 text-xs font-semibold uppercase tracking-[0.18em] text-slate-600 transition hover:border-cyan-200 hover:text-slate-900">
                                    Editar
                                </a>

                                <form action="{{ route('admin.product-brands.destroy', $brand) }}" method="POST" onsubmit="return confirm('Se eliminara la marca seleccionada.');">
                                    @csrf
                                    @method('DELETE')

                                    <button type="submit" class="rounded-2xl border border-rose-200 bg-rose-50 px-3 py-2 text-xs font-semibold uppercase tracking-[0.18em] text-rose-700 transition hover:bg-rose-100" @disabled($brand->products_count > 0)>
                                        Eliminar
                                    </button>
                                </form>
                            </div>
                        </div>

                        <div class="mt-6 grid gap-4 md:grid-cols-2">
                            <div class="rounded-[24px] border border-slate-200 bg-white/85 p-4">
                                <p class="text-xs uppercase tracking-[0.24em] text-slate-400">Productos</p>
                                <p class="mt-2 text-2xl font-semibold text-slate-900">{{ $brand->products_count }}</p>
                            </div>

                            <div class="rounded-[24px] border border-slate-200 bg-white/85 p-4">
                                <p class="text-xs uppercase tracking-[0.24em] text-slate-400">Logo</p>
                                <p class="mt-2 text-2xl font-semibold text-slate-900">{{ $brand->hasLogo() ? 'Cargado' : 'Pendiente' }}</p>
                            </div>
                        </div>
                    </article>
                @empty
                    <article class="app-panel rounded-[34px] px-6 py-12 text-center">
                        <p class="font-display text-2xl font-semibold text-slate-900">Todavia no hay marcas creadas</p>
                        <p class="mx-auto mt-3 max-w-xl text-sm leading-6 text-slate-600">Crea marcas con su logo para normalizar productos y preparar filtros por marca.</p>
                        <a href="{{ route('admin.product-brands.create') }}" class="mt-6 inline-flex items-center justify-center rounded-2xl bg-slate-950 px-4 py-3 text-sm font-semibold text-white transition hover:bg-cyan-700">
                            Crear marca
                        </a>
                    </article>
                @endforelse

                @if ($brands->hasPages())
                    <div class="pt-2">
                        {{ $brands->links() }}
                    </div>
                @endif
            </div>

            <aside class="space-y-6">
                <article class="app-panel rounded-[34px] p-6">
                    <p class="text-sm font-medium text-slate-500">Uso recomendado</p>
                    <h2 class="font-display mt-2 text-2xl font-semibold tracking-tight text-slate-950">Marca normalizada</h2>
                    <p class="mt-4 text-sm leading-7 text-slate-600">
                        Usa este modulo para evitar variaciones como Philips, PHILIPS o Philips Healthcare y asi construir filtros realmente consistentes.
                    </p>
                </article>

                <article class="app-panel rounded-[34px] p-6">
                    <p class="text-sm font-medium text-slate-500">Siguiente paso</p>
                    <a href="{{ route('admin.products.create') }}" class="mt-4 block rounded-[24px] border border-slate-200 bg-white/85 p-4 transition hover:border-cyan-200">
                        <p class="text-sm font-semibold text-slate-900">Crear producto con marca</p>
                        <p class="mt-2 text-sm leading-6 text-slate-600">Asigna una marca del catalogo para que el producto quede listo para filtros posteriores.</p>
                    </a>
                </article>
            </aside>
        </section>
    </div>
@endsection
