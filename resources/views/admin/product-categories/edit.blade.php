@extends('layouts.admin')

@section('title', 'Editar categoria')
@section('page-kicker', 'Catalogo biomedico')
@section('page-title', 'Editar categoria')
@section('page-description', 'Actualiza la estructura de clasificacion del catalogo biomedico.')

@section('header-actions')
    <a href="{{ route('admin.product-categories.index') }}" class="inline-flex items-center justify-center rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm font-semibold text-slate-700 shadow-sm transition hover:border-cyan-200 hover:text-slate-950">
        Volver al listado
    </a>
@endsection

@section('content')
    <div class="space-y-6">
        @include('admin.products.tabs')

        <form action="{{ route('admin.product-categories.update', $category) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            @include('admin.product-categories.partials.form', ['submitLabel' => 'Guardar cambios'])
        </form>
    </div>
@endsection
