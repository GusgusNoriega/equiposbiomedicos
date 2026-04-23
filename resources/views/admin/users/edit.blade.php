@extends('layouts.admin')

@section('title', 'Editar usuario')
@section('page-kicker', 'Modulo de acceso')
@section('page-title', 'Editar usuario')
@section('page-description', 'Actualiza los datos del usuario y cambia su rol operativo cuando sea necesario.')

@section('header-actions')
    <a href="{{ route('admin.users.index') }}" class="inline-flex items-center justify-center rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm font-semibold text-slate-700 shadow-sm transition hover:border-cyan-200 hover:text-slate-950">
        Volver al listado
    </a>
@endsection

@section('content')
    <div class="space-y-6">
        @include('admin.access.tabs')

        <form action="{{ route('admin.users.update', $user) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            @include('admin.users.partials.form', ['submitLabel' => 'Guardar cambios'])
        </form>
    </div>
@endsection
