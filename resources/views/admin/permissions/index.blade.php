@extends('layouts.admin')

@section('title', 'Permisos')
@section('page-kicker', 'Modulo de acceso')
@section('page-title', 'Permisos del sistema')
@section('page-description', 'Mantiene las acciones disponibles para los roles del administrador y organiza el control de acceso por modulo.')

@section('header-actions')
    <a href="{{ route('admin.permissions.create') }}" class="inline-flex items-center justify-center rounded-2xl bg-slate-950 px-4 py-3 text-sm font-semibold text-white shadow-lg shadow-slate-950/15 transition hover:bg-cyan-700">
        Nuevo permiso
    </a>
@endsection

@section('content')
    <div class="space-y-6">
        @include('admin.access.tabs')

        <section class="grid gap-4 md:grid-cols-3">
            <article class="app-panel rounded-[28px] p-5">
                <p class="text-sm font-medium text-slate-500">Permisos totales</p>
                <p class="font-display mt-3 text-4xl font-semibold tracking-tight text-slate-950">{{ $stats['permissions'] }}</p>
                <p class="mt-2 text-sm leading-6 text-slate-600">Acciones registradas dentro del modelo de seguridad.</p>
            </article>

            <article class="app-panel rounded-[28px] p-5">
                <p class="text-sm font-medium text-slate-500">Modulos cubiertos</p>
                <p class="font-display mt-3 text-4xl font-semibold tracking-tight text-slate-950">{{ $stats['modules'] }}</p>
                <p class="mt-2 text-sm leading-6 text-slate-600">Agrupaciones funcionales para ordenar permisos por area.</p>
            </article>

            <article class="app-panel rounded-[28px] p-5">
                <p class="text-sm font-medium text-slate-500">Permisos en uso</p>
                <p class="font-display mt-3 text-4xl font-semibold tracking-tight text-slate-950">{{ $stats['assigned'] }}</p>
                <p class="mt-2 text-sm leading-6 text-slate-600">Permisos que ya estan vinculados a algun rol.</p>
            </article>
        </section>

        <section class="grid gap-6 xl:grid-cols-[minmax(0,1.35fr)_360px]">
            <article class="app-panel rounded-[34px] p-6">
                <div class="flex flex-wrap items-center justify-between gap-3">
                    <div>
                        <p class="text-sm font-medium text-slate-500">Catalogo</p>
                        <h2 class="font-display mt-2 text-2xl font-semibold tracking-tight text-slate-950">Permisos registrados</h2>
                    </div>

                    <span class="rounded-full border border-slate-200 bg-white/80 px-4 py-2 text-sm font-medium text-slate-600 shadow-sm">
                        {{ $permissions->total() }} registros
                    </span>
                </div>

                <div class="mt-6 overflow-hidden rounded-[26px] border border-slate-200">
                    @if ($permissions->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-slate-200 bg-white/80 text-sm">
                                <thead class="bg-slate-950/4 text-left text-xs font-semibold uppercase tracking-[0.24em] text-slate-500">
                                    <tr>
                                        <th class="px-5 py-4">Permiso</th>
                                        <th class="px-5 py-4">Clave</th>
                                        <th class="px-5 py-4">Modulo</th>
                                        <th class="px-5 py-4">Roles</th>
                                        <th class="px-5 py-4 text-right">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-200">
                                    @foreach ($permissions as $permission)
                                        <tr class="align-top">
                                            <td class="px-5 py-4">
                                                <p class="font-semibold text-slate-900">{{ $permission->name }}</p>
                                                <p class="mt-1 text-sm leading-6 text-slate-600">{{ $permission->description ?: 'Sin descripcion operativa.' }}</p>
                                            </td>
                                            <td class="px-5 py-4">
                                                <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-600">
                                                    {{ $permission->code }}
                                                </span>
                                            </td>
                                            <td class="px-5 py-4 text-slate-600">{{ $permission->module ? \Illuminate\Support\Str::headline($permission->module) : 'General' }}</td>
                                            <td class="px-5 py-4 text-slate-600">{{ $permission->roles_count }}</td>
                                            <td class="px-5 py-4">
                                                <div class="flex justify-end gap-2">
                                                    <a href="{{ route('admin.permissions.edit', $permission) }}" class="rounded-2xl border border-slate-200 bg-white px-3 py-2 text-xs font-semibold uppercase tracking-[0.18em] text-slate-600 transition hover:border-cyan-200 hover:text-slate-900">
                                                        Editar
                                                    </a>

                                                    <form action="{{ route('admin.permissions.destroy', $permission) }}" method="POST" onsubmit="return confirm('Se eliminara el permiso seleccionado.');">
                                                        @csrf
                                                        @method('DELETE')

                                                        <button type="submit" class="rounded-2xl border border-rose-200 bg-rose-50 px-3 py-2 text-xs font-semibold uppercase tracking-[0.18em] text-rose-700 transition hover:bg-rose-100" @disabled($permission->roles_count > 0)>
                                                            Eliminar
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="px-6 py-12 text-center">
                            <p class="font-display text-2xl font-semibold text-slate-900">Todavia no hay permisos definidos</p>
                            <p class="mx-auto mt-3 max-w-xl text-sm leading-6 text-slate-600">Crea las acciones que luego vas a asignar a los roles del administrador.</p>
                            <a href="{{ route('admin.permissions.create') }}" class="mt-6 inline-flex items-center justify-center rounded-2xl bg-slate-950 px-4 py-3 text-sm font-semibold text-white transition hover:bg-cyan-700">
                                Crear permiso
                            </a>
                        </div>
                    @endif
                </div>

                @if ($permissions->hasPages())
                    <div class="mt-6">
                        {{ $permissions->links() }}
                    </div>
                @endif
            </article>

            <aside class="space-y-6">
                <article class="app-panel rounded-[34px] p-6">
                    <p class="text-sm font-medium text-slate-500">Recomendacion</p>
                    <h2 class="font-display mt-2 text-2xl font-semibold tracking-tight text-slate-950">Agrupa por modulo</h2>
                    <p class="mt-4 text-sm leading-7 text-slate-600">
                        Usa modulos como `usuarios`, `equipos`, `mantenimiento` o `cumplimiento` para mantener un catalogo de permisos claro.
                    </p>
                </article>

                <article class="app-panel rounded-[34px] p-6">
                    <p class="text-sm font-medium text-slate-500">Uso inmediato</p>
                    <a href="{{ route('admin.roles.index') }}" class="mt-4 block rounded-[24px] border border-slate-200 bg-white/85 p-4 transition hover:border-cyan-200">
                        <p class="text-sm font-semibold text-slate-900">Asignar permisos a roles</p>
                        <p class="mt-2 text-sm leading-6 text-slate-600">Ve al modulo de roles para conectar estas acciones con cada perfil.</p>
                    </a>
                </article>
            </aside>
        </section>
    </div>
@endsection
