@extends('layouts.admin')

@section('title', 'Crear rol')
@section('page-kicker', 'Modulo de acceso')
@section('page-title', 'Nuevo rol')
@section('page-description', 'Construye un perfil funcional y selecciona los permisos que debera heredar cada usuario asignado.')

@section('header-actions')
    <a href="{{ route('admin.roles.index') }}" class="inline-flex items-center justify-center rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm font-semibold text-slate-700 shadow-sm transition hover:border-cyan-200 hover:text-slate-950">
        Volver al listado
    </a>
@endsection

@section('content')
    <div class="space-y-6">
        @include('admin.access.tabs')

        <form action="{{ route('admin.roles.store') }}" method="POST" class="space-y-6">
            @csrf

            @include('admin.roles.partials.form', ['submitLabel' => 'Crear rol'])
        </form>
    </div>
@endsection
