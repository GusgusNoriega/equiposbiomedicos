@extends('layouts.admin')

@section('title', 'Crear usuario')
@section('page-kicker', 'Modulo de acceso')
@section('page-title', 'Nuevo usuario')
@section('page-description', 'Registra una cuenta administrativa y asignale el rol que definira sus permisos.')

@section('header-actions')
    <a href="{{ route('admin.users.index') }}" class="inline-flex items-center justify-center rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm font-semibold text-slate-700 shadow-sm transition hover:border-cyan-200 hover:text-slate-950">
        Volver al listado
    </a>
@endsection

@section('content')
    <div class="space-y-6">
        @include('admin.access.tabs')

        <form action="{{ route('admin.users.store') }}" method="POST" class="space-y-6">
            @csrf

            @include('admin.users.partials.form', ['submitLabel' => 'Crear usuario'])
        </form>
    </div>
@endsection
