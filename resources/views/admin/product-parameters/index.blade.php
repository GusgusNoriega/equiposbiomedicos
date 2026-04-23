@extends('layouts.admin')

@section('title', 'Parametros filtrables')
@section('page-kicker', 'Catalogo biomedico')
@section('page-title', 'Parametros filtrables')
@section('page-description', 'Administra los parametros reutilizables y sus unidades para asignarlos despues a los productos biomedicos.')

@section('header-actions')
    <a href="{{ route('admin.product-parameters.create') }}" class="inline-flex items-center justify-center rounded-2xl bg-slate-950 px-4 py-3 text-sm font-semibold text-white shadow-lg shadow-slate-950/15 transition hover:bg-cyan-700">
        Nuevo parametro
    </a>
@endsection

@section('content')
    <div class="space-y-6">
        @include('admin.products.tabs')

        <section class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
            <article class="app-panel rounded-[28px] p-5">
                <p class="text-sm font-medium text-slate-500">Parametros</p>
                <p class="font-display mt-3 text-4xl font-semibold tracking-tight text-slate-950">{{ $stats['parameters'] }}</p>
                <p class="mt-2 text-sm leading-6 text-slate-600">Campos normalizados disponibles para el catalogo.</p>
            </article>

            <article class="app-panel rounded-[28px] p-5">
                <p class="text-sm font-medium text-slate-500">Activos</p>
                <p class="font-display mt-3 text-4xl font-semibold tracking-tight text-slate-950">{{ $stats['active'] }}</p>
                <p class="mt-2 text-sm leading-6 text-slate-600">Parametros listos para usarse en nuevos productos.</p>
            </article>

            <article class="app-panel rounded-[28px] p-5">
                <p class="text-sm font-medium text-slate-500">Unidades</p>
                <p class="font-display mt-3 text-4xl font-semibold tracking-tight text-slate-950">{{ $stats['units'] }}</p>
                <p class="mt-2 text-sm leading-6 text-slate-600">Simbolos y medidas asociadas a los parametros.</p>
            </article>

            <article class="app-panel rounded-[28px] p-5">
                <p class="text-sm font-medium text-slate-500">Valores asignados</p>
                <p class="font-display mt-3 text-4xl font-semibold tracking-tight text-slate-950">{{ $stats['assigned'] }}</p>
                <p class="mt-2 text-sm leading-6 text-slate-600">Registros ya usados dentro de productos del catalogo.</p>
            </article>
        </section>

        <section class="grid gap-6 xl:grid-cols-[minmax(0,1.35fr)_360px]">
            <div class="space-y-4">
                @forelse ($parameters as $parameter)
                    <article class="app-panel rounded-[34px] p-6">
                        <div class="flex flex-wrap items-start justify-between gap-4">
                            <div class="min-w-0">
                                <div class="flex flex-wrap items-center gap-2">
                                    <h2 class="font-display text-2xl font-semibold tracking-tight text-slate-950">{{ $parameter->name }}</h2>
                                    <span class="rounded-full px-3 py-1 text-xs font-semibold {{ $parameter->is_active ? 'bg-emerald-50 text-emerald-700' : 'bg-slate-100 text-slate-600' }}">
                                        {{ $parameter->is_active ? 'Activo' : 'Inactivo' }}
                                    </span>
                                    <span class="rounded-full px-3 py-1 text-xs font-semibold {{ $parameter->is_filterable ? 'bg-cyan-50 text-cyan-700' : 'bg-slate-100 text-slate-600' }}">
                                        {{ $parameter->is_filterable ? 'Filtrable' : 'Solo informativo' }}
                                    </span>
                                </div>

                                <p class="mt-2 text-sm uppercase tracking-[0.22em] text-slate-400">{{ $parameter->code }}</p>
                                <p class="mt-4 text-sm leading-7 text-slate-600">{{ $parameter->description ?: 'Este parametro aun no tiene una descripcion funcional.' }}</p>
                            </div>

                            <div class="flex flex-wrap gap-2">
                                <a href="{{ route('admin.product-parameters.edit', $parameter) }}" class="rounded-2xl border border-slate-200 bg-white px-3 py-2 text-xs font-semibold uppercase tracking-[0.18em] text-slate-600 transition hover:border-cyan-200 hover:text-slate-900">
                                    Editar
                                </a>

                                <form action="{{ route('admin.product-parameters.destroy', $parameter) }}" method="POST" onsubmit="return confirm('Se eliminara el parametro seleccionado.');">
                                    @csrf
                                    @method('DELETE')

                                    <button type="submit" class="rounded-2xl border border-rose-200 bg-rose-50 px-3 py-2 text-xs font-semibold uppercase tracking-[0.18em] text-rose-700 transition hover:bg-rose-100" @disabled($parameter->values_count > 0)>
                                        Eliminar
                                    </button>
                                </form>
                            </div>
                        </div>

                        <div class="mt-6 grid gap-4 md:grid-cols-3">
                            <div class="rounded-[24px] border border-slate-200 bg-white/85 p-4">
                                <p class="text-xs uppercase tracking-[0.24em] text-slate-400">Tipo</p>
                                <p class="mt-2 text-2xl font-semibold text-slate-900">{{ $parameter->value_type === 'number' ? 'Numerico' : 'Texto' }}</p>
                            </div>

                            <div class="rounded-[24px] border border-slate-200 bg-white/85 p-4">
                                <p class="text-xs uppercase tracking-[0.24em] text-slate-400">Unidades</p>
                                <p class="mt-2 text-2xl font-semibold text-slate-900">{{ $parameter->units_count }}</p>
                            </div>

                            <div class="rounded-[24px] border border-slate-200 bg-white/85 p-4">
                                <p class="text-xs uppercase tracking-[0.24em] text-slate-400">Productos con valor</p>
                                <p class="mt-2 text-2xl font-semibold text-slate-900">{{ $parameter->values_count }}</p>
                            </div>
                        </div>

                        <div class="mt-5 flex flex-wrap gap-2">
                            @forelse ($parameter->units as $unit)
                                <span class="rounded-full border border-slate-200 bg-white px-3 py-2 text-xs font-semibold uppercase tracking-[0.16em] text-slate-600">
                                    {{ $unit->name }}@if($unit->symbol) ({{ $unit->symbol }})@endif
                                </span>
                            @empty
                                <span class="rounded-full border border-dashed border-slate-300 bg-slate-50 px-3 py-2 text-xs font-semibold uppercase tracking-[0.16em] text-slate-500">
                                    Sin unidades definidas
                                </span>
                            @endforelse
                        </div>
                    </article>
                @empty
                    <article class="app-panel rounded-[34px] px-6 py-12 text-center">
                        <p class="font-display text-2xl font-semibold text-slate-900">Todavia no hay parametros creados</p>
                        <p class="mx-auto mt-3 max-w-xl text-sm leading-6 text-slate-600">Crea parametros como peso, autonomia, capacidad o potencia para luego asignarlos a los productos del catalogo.</p>
                        <a href="{{ route('admin.product-parameters.create') }}" class="mt-6 inline-flex items-center justify-center rounded-2xl bg-slate-950 px-4 py-3 text-sm font-semibold text-white transition hover:bg-cyan-700">
                            Crear parametro
                        </a>
                    </article>
                @endforelse

                @if ($parameters->hasPages())
                    <div class="pt-2">
                        {{ $parameters->links() }}
                    </div>
                @endif
            </div>

            <aside class="space-y-6">
                <article class="app-panel rounded-[34px] p-6">
                    <p class="text-sm font-medium text-slate-500">Diseno recomendado</p>
                    <h2 class="font-display mt-2 text-2xl font-semibold tracking-tight text-slate-950">Un parametro por concepto</h2>
                    <p class="mt-4 text-sm leading-7 text-slate-600">
                        Separa conceptos como peso, ancho, autonomia o potencia para que cada uno pueda filtrarse despues por valor o rango.
                    </p>
                </article>

                <article class="app-panel rounded-[34px] p-6">
                    <p class="text-sm font-medium text-slate-500">Siguiente paso</p>
                    <a href="{{ route('admin.products.create') }}" class="mt-4 block rounded-[24px] border border-slate-200 bg-white/85 p-4 transition hover:border-cyan-200">
                        <p class="text-sm font-semibold text-slate-900">Asignar parametros a un producto</p>
                        <p class="mt-2 text-sm leading-6 text-slate-600">Una vez creado el catalogo de parametros, podras capturar sus valores desde el formulario del producto.</p>
                    </a>
                </article>
            </aside>
        </section>
    </div>
@endsection
