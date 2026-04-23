@extends('layouts.admin')

@section('title', 'Crear marca')
@section('page-kicker', 'Catalogo biomedico')
@section('page-title', 'Nueva marca')
@section('page-description', 'Registra una marca de producto y carga su logo para reutilizarla dentro del catalogo.')

@section('header-actions')
    <a href="{{ route('admin.product-brands.index') }}" class="inline-flex items-center justify-center rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm font-semibold text-slate-700 shadow-sm transition hover:border-cyan-200 hover:text-slate-950">
        Volver al listado
    </a>
@endsection

@section('content')
    <div class="space-y-6">
        @include('admin.products.tabs')

        <form action="{{ route('admin.product-brands.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            @include('admin.product-brands.partials.form', ['submitLabel' => 'Crear marca'])
        </form>
    </div>
@endsection
