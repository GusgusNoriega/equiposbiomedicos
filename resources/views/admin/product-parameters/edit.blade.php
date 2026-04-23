@extends('layouts.admin')

@section('title', 'Editar parametro')
@section('page-kicker', 'Catalogo biomedico')
@section('page-title', 'Editar parametro filtrable')
@section('page-description', 'Actualiza el parametro, sus unidades disponibles y su uso futuro dentro del catalogo de productos.')

@section('header-actions')
    <a href="{{ route('admin.product-parameters.index') }}" class="inline-flex items-center justify-center rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm font-semibold text-slate-700 shadow-sm transition hover:border-cyan-200 hover:text-slate-950">
        Volver al listado
    </a>
@endsection

@section('content')
    <div class="space-y-6">
        @include('admin.products.tabs')

        <form action="{{ route('admin.product-parameters.update', $parameter) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            @include('admin.product-parameters.partials.form', ['submitLabel' => 'Guardar cambios'])
        </form>

        @include('admin.product-parameters.partials.units-script')
    </div>
@endsection
