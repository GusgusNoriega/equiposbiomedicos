@extends('layouts.admin')

@section('title', 'Categorias de productos')
@section('page-kicker', 'Catalogo biomedico')
@section('page-title', 'Categorias de productos')
@section('page-description', 'Organiza el catalogo biomedico por familias funcionales para facilitar la clasificacion y el filtrado de productos.')

@section('header-actions')
    <a href="{{ route('admin.product-categories.create') }}" class="inline-flex items-center justify-center rounded-2xl bg-slate-950 px-4 py-3 text-sm font-semibold text-white shadow-lg shadow-slate-950/15 transition hover:bg-cyan-700">
        Nueva categoria
    </a>
@endsection

@section('content')
    <div class="space-y-6">
        @include('admin.products.tabs')

        <section class="grid gap-4 md:grid-cols-3">
            <article class="app-panel rounded-[28px] p-5">
                <p class="text-sm font-medium text-slate-500">Categorias</p>
                <p class="font-display mt-3 text-4xl font-semibold tracking-tight text-slate-950">{{ $stats['categories'] }}</p>
                <p class="mt-2 text-sm leading-6 text-slate-600">Clasificaciones funcionales para el catalogo.</p>
            </article>

            <article class="app-panel rounded-[28px] p-5">
                <p class="text-sm font-medium text-slate-500">Activas</p>
                <p class="font-display mt-3 text-4xl font-semibold tracking-tight text-slate-950">{{ $stats['active'] }}</p>
                <p class="mt-2 text-sm leading-6 text-slate-600">Categorias disponibles para asignar a nuevos productos.</p>
            </article>

            <article class="app-panel rounded-[28px] p-5">
                <p class="text-sm font-medium text-slate-500">Productos asociados</p>
                <p class="font-display mt-3 text-4xl font-semibold tracking-tight text-slate-950">{{ $stats['products'] }}</p>
                <p class="mt-2 text-sm leading-6 text-slate-600">Total de productos que usan este modulo del catalogo.</p>
            </article>
        </section>

        <section class="grid gap-6 xl:grid-cols-[minmax(0,1.35fr)_360px]">
            <div class="space-y-4">
                @forelse ($categories as $category)
                    <article class="app-panel rounded-[34px] p-6">
                        <div class="flex flex-wrap items-start justify-between gap-4">
                            <div class="min-w-0">
                                <div class="flex flex-wrap items-center gap-2">
                                    <h2 class="font-display text-2xl font-semibold tracking-tight text-slate-950">{{ $category->name }}</h2>
                                    <span class="rounded-full px-3 py-1 text-xs font-semibold {{ $category->is_active ? 'bg-emerald-50 text-emerald-700' : 'bg-slate-100 text-slate-600' }}">
                                        {{ $category->is_active ? 'Activa' : 'Inactiva' }}
                                    </span>
                                </div>

                                <p class="mt-2 text-sm uppercase tracking-[0.22em] text-slate-400">{{ $category->code }}</p>
                                <p class="mt-4 text-sm leading-7 text-slate-600">{{ $category->description ?: 'Esta categoria aun no tiene una descripcion funcional.' }}</p>
                            </div>

                            <div class="flex flex-wrap gap-2">
                                <a href="{{ route('admin.product-categories.edit', $category) }}" class="rounded-2xl border border-slate-200 bg-white px-3 py-2 text-xs font-semibold uppercase tracking-[0.18em] text-slate-600 transition hover:border-cyan-200 hover:text-slate-900">
                                    Editar
                                </a>

                                <form action="{{ route('admin.product-categories.destroy', $category) }}" method="POST" onsubmit="return confirm('Se eliminara la categoria seleccionada.');">
                                    @csrf
                                    @method('DELETE')

                                    <button type="submit" class="rounded-2xl border border-rose-200 bg-rose-50 px-3 py-2 text-xs font-semibold uppercase tracking-[0.18em] text-rose-700 transition hover:bg-rose-100" @disabled($category->products_count > 0)>
                                        Eliminar
                                    </button>
                                </form>
                            </div>
                        </div>

                        <div class="mt-6 grid gap-4 md:grid-cols-2">
                            <div class="rounded-[24px] border border-slate-200 bg-white/85 p-4">
                                <p class="text-xs uppercase tracking-[0.24em] text-slate-400">Productos</p>
                                <p class="mt-2 text-2xl font-semibold text-slate-900">{{ $category->products_count }}</p>
                            </div>

                            <div class="rounded-[24px] border border-slate-200 bg-white/85 p-4">
                                <p class="text-xs uppercase tracking-[0.24em] text-slate-400">Estado</p>
                                <p class="mt-2 text-2xl font-semibold text-slate-900">{{ $category->is_active ? 'Disponible' : 'Oculta' }}</p>
                            </div>
                        </div>
                    </article>
                @empty
                    <article class="app-panel rounded-[34px] px-6 py-12 text-center">
                        <p class="font-display text-2xl font-semibold text-slate-900">Todavia no hay categorias creadas</p>
                        <p class="mx-auto mt-3 max-w-xl text-sm leading-6 text-slate-600">Crea primero las categorias funcionales antes de registrar productos biomedicos.</p>
                        <a href="{{ route('admin.product-categories.create') }}" class="mt-6 inline-flex items-center justify-center rounded-2xl bg-slate-950 px-4 py-3 text-sm font-semibold text-white transition hover:bg-cyan-700">
                            Crear categoria
                        </a>
                    </article>
                @endforelse

                @if ($categories->hasPages())
                    <div class="pt-2">
                        {{ $categories->links() }}
                    </div>
                @endif
            </div>

            <aside class="space-y-6">
                <article class="app-panel rounded-[34px] p-6">
                    <p class="text-sm font-medium text-slate-500">Uso recomendado</p>
                    <h2 class="font-display mt-2 text-2xl font-semibold tracking-tight text-slate-950">Agrupa por familia</h2>
                    <p class="mt-4 text-sm leading-7 text-slate-600">
                        Crea categorias como monitorizacion, soporte vital, imagenologia o laboratorio para ordenar el catalogo.
                    </p>
                </article>

                <article class="app-panel rounded-[34px] p-6">
                    <p class="text-sm font-medium text-slate-500">Siguiente paso</p>
                    <a href="{{ route('admin.products.create') }}" class="mt-4 block rounded-[24px] border border-slate-200 bg-white/85 p-4 transition hover:border-cyan-200">
                        <p class="text-sm font-semibold text-slate-900">Crear producto biomedico</p>
                        <p class="mt-2 text-sm leading-6 text-slate-600">Una vez creada la categoria, podras registrar productos y sus especificaciones tecnicas.</p>
                    </a>
                </article>
            </aside>
        </section>
    </div>
@endsection
