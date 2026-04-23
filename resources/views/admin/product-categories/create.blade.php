@extends('layouts.admin')

@section('title', 'Crear categoria')
@section('page-kicker', 'Catalogo biomedico')
@section('page-title', 'Nueva categoria')
@section('page-description', 'Define una familia funcional para clasificar productos biomedicos dentro del administrador.')

@section('header-actions')
    <a href="{{ route('admin.product-categories.index') }}" class="inline-flex items-center justify-center rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm font-semibold text-slate-700 shadow-sm transition hover:border-cyan-200 hover:text-slate-950">
        Volver al listado
    </a>
@endsection

@section('content')
    <div class="space-y-6">
        @include('admin.products.tabs')

        <form action="{{ route('admin.product-categories.store') }}" method="POST" class="space-y-6">
            @csrf

            @include('admin.product-categories.partials.form', ['submitLabel' => 'Crear categoria'])
        </form>
    </div>
@endsection
