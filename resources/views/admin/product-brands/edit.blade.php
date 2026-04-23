@extends('layouts.admin')

@section('title', 'Editar marca')
@section('page-kicker', 'Catalogo biomedico')
@section('page-title', 'Editar marca')
@section('page-description', 'Actualiza el nombre, el logo y el estado operativo de la marca dentro del catalogo.')

@section('header-actions')
    <a href="{{ route('admin.product-brands.index') }}" class="inline-flex items-center justify-center rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm font-semibold text-slate-700 shadow-sm transition hover:border-cyan-200 hover:text-slate-950">
        Volver al listado
    </a>
@endsection

@section('content')
    <div class="space-y-6">
        @include('admin.products.tabs')

        <form action="{{ route('admin.product-brands.update', $productBrand) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            @include('admin.product-brands.partials.form', ['submitLabel' => 'Guardar cambios'])
        </form>
    </div>
@endsection
