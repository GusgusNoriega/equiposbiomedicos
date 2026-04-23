@extends('layouts.admin')

@section('title', 'Crear permiso')
@section('page-kicker', 'Modulo de acceso')
@section('page-title', 'Nuevo permiso')
@section('page-description', 'Define una accion del sistema y agrupa su alcance dentro del modulo correspondiente.')

@section('header-actions')
    <a href="{{ route('admin.permissions.index') }}" class="inline-flex items-center justify-center rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm font-semibold text-slate-700 shadow-sm transition hover:border-cyan-200 hover:text-slate-950">
        Volver al listado
    </a>
@endsection

@section('content')
    <div class="space-y-6">
        @include('admin.access.tabs')

        <form action="{{ route('admin.permissions.store') }}" method="POST" class="space-y-6">
            @csrf

            @include('admin.permissions.partials.form', ['submitLabel' => 'Crear permiso'])
        </form>
    </div>
@endsection
