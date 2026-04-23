@extends('layouts.admin')

@section('title', 'Crear producto biomedico')
@section('page-kicker', 'Catalogo biomedico')
@section('page-title', 'Nuevo producto biomedico')
@section('page-description', 'Registra un producto, asignale una categoria y carga sus descripciones y especificaciones tecnicas.')

@section('header-actions')
    <a href="{{ route('admin.products.index') }}" class="inline-flex items-center justify-center rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm font-semibold text-slate-700 shadow-sm transition hover:border-cyan-200 hover:text-slate-950">
        Volver al listado
    </a>
@endsection

@section('content')
    <div class="space-y-6">
        @include('admin.products.tabs')

        <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            @include('admin.products.partials.form', ['submitLabel' => 'Crear producto'])
        </form>

        @include('admin.products.partials.specifications-script')
        @include('admin.products.partials.parameter-values-script')
    </div>
@endsection
