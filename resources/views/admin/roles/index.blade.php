@extends('layouts.admin')

@section('title', 'Roles')
@section('page-kicker', 'Modulo de acceso')
@section('page-title', 'Roles del administrador')
@section('page-description', 'Define perfiles funcionales para el area biomedica y agrupa permisos reutilizables para cada tipo de usuario.')

@section('header-actions')
    <a href="{{ route('admin.roles.create') }}" class="inline-flex items-center justify-center rounded-2xl bg-slate-950 px-4 py-3 text-sm font-semibold text-white shadow-lg shadow-slate-950/15 transition hover:bg-cyan-700">
        Nuevo rol
    </a>
@endsection

@section('content')
    <div class="space-y-6">
        @include('admin.access.tabs')

        <section class="grid gap-4 md:grid-cols-3">
            <article class="app-panel rounded-[28px] p-5">
                <p class="text-sm font-medium text-slate-500">Roles totales</p>
                <p class="font-display mt-3 text-4xl font-semibold tracking-tight text-slate-950">{{ $stats['roles'] }}</p>
                <p class="mt-2 text-sm leading-6 text-slate-600">Perfiles disponibles para la operacion administrativa.</p>
            </article>

            <article class="app-panel rounded-[28px] p-5">
                <p class="text-sm font-medium text-slate-500">Roles de sistema</p>
                <p class="font-display mt-3 text-4xl font-semibold tracking-tight text-slate-950">{{ $stats['system_roles'] }}</p>
                <p class="mt-2 text-sm leading-6 text-slate-600">Perfiles base protegidos para conservar el acceso inicial.</p>
            </article>

            <article class="app-panel rounded-[28px] p-5">
                <p class="text-sm font-medium text-slate-500">Permisos disponibles</p>
                <p class="font-display mt-3 text-4xl font-semibold tracking-tight text-slate-950">{{ $stats['permissions'] }}</p>
                <p class="mt-2 text-sm leading-6 text-slate-600">Acciones que puedes combinar para formar cada rol.</p>
            </article>
        </section>

        <section class="grid gap-6 xl:grid-cols-[minmax(0,1.35fr)_360px]">
            <div class="space-y-4">
                @forelse ($roles as $role)
                    <article class="app-panel rounded-[34px] p-6">
                        <div class="flex flex-wrap items-start justify-between gap-4">
                            <div class="min-w-0">
                                <div class="flex flex-wrap items-center gap-2">
                                    <h2 class="font-display text-2xl font-semibold tracking-tight text-slate-950">{{ $role->name }}</h2>
                                    <span class="rounded-full px-3 py-1 text-xs font-semibold {{ $role->is_system ? 'bg-cyan-50 text-cyan-700' : 'bg-slate-100 text-slate-600' }}">
                                        {{ $role->badge_label }}
                                    </span>
                                </div>

                                <p class="mt-2 text-sm uppercase tracking-[0.22em] text-slate-400">{{ $role->code }}</p>
                                <p class="mt-4 text-sm leading-7 text-slate-600">{{ $role->description ?: 'Este rol aun no tiene una descripcion funcional.' }}</p>
                            </div>

                            <div class="flex flex-wrap gap-2">
                                <a href="{{ route('admin.roles.edit', $role) }}" class="rounded-2xl border border-slate-200 bg-white px-3 py-2 text-xs font-semibold uppercase tracking-[0.18em] text-slate-600 transition hover:border-cyan-200 hover:text-slate-900">
                                    Editar
                                </a>

                                <form action="{{ route('admin.roles.destroy', $role) }}" method="POST" onsubmit="return confirm('Se eliminara el rol seleccionado.');">
                                    @csrf
                                    @method('DELETE')

                                    <button type="submit" class="rounded-2xl border border-rose-200 bg-rose-50 px-3 py-2 text-xs font-semibold uppercase tracking-[0.18em] text-rose-700 transition hover:bg-rose-100" @disabled($role->is_system || $role->users_count > 0)>
                                        Eliminar
                                    </button>
                                </form>
                            </div>
                        </div>

                        <div class="mt-6 grid gap-4 md:grid-cols-3">
                            <div class="rounded-[24px] border border-slate-200 bg-white/85 p-4">
                                <p class="text-xs uppercase tracking-[0.24em] text-slate-400">Usuarios</p>
                                <p class="mt-2 text-2xl font-semibold text-slate-900">{{ $role->users_count }}</p>
                            </div>

                            <div class="rounded-[24px] border border-slate-200 bg-white/85 p-4">
                                <p class="text-xs uppercase tracking-[0.24em] text-slate-400">Permisos</p>
                                <p class="mt-2 text-2xl font-semibold text-slate-900">{{ $role->permissions_count }}</p>
                            </div>

                            <div class="rounded-[24px] border border-slate-200 bg-white/85 p-4">
                                <p class="text-xs uppercase tracking-[0.24em] text-slate-400">Tipo</p>
                                <p class="mt-2 text-2xl font-semibold text-slate-900">{{ $role->is_system ? 'Base' : 'Custom' }}</p>
                            </div>
                        </div>

                        <div class="mt-6">
                            <p class="text-sm font-medium text-slate-500">Permisos asociados</p>
                            <div class="mt-3 flex flex-wrap gap-2">
                                @forelse ($role->permissions->take(8) as $permission)
                                    <span class="rounded-full bg-cyan-50 px-3 py-1.5 text-xs font-semibold text-cyan-700">
                                        {{ $permission->name }}
                                    </span>
                                @empty
                                    <span class="rounded-full bg-slate-100 px-3 py-1.5 text-xs font-semibold text-slate-600">
                                        Sin permisos asignados
                                    </span>
                                @endforelse

                                @if ($role->permissions_count > 8)
                                    <span class="rounded-full bg-slate-100 px-3 py-1.5 text-xs font-semibold text-slate-600">
                                        +{{ $role->permissions_count - 8 }} mas
                                    </span>
                                @endif
                            </div>
                        </div>
                    </article>
                @empty
                    <article class="app-panel rounded-[34px] px-6 py-12 text-center">
                        <p class="font-display text-2xl font-semibold text-slate-900">Todavia no hay roles personalizados</p>
                        <p class="mx-auto mt-3 max-w-xl text-sm leading-6 text-slate-600">Empieza creando el rol que usara cada tipo de usuario en el area biomedica.</p>
                        <a href="{{ route('admin.roles.create') }}" class="mt-6 inline-flex items-center justify-center rounded-2xl bg-slate-950 px-4 py-3 text-sm font-semibold text-white transition hover:bg-cyan-700">
                            Crear rol
                        </a>
                    </article>
                @endforelse

                @if ($roles->hasPages())
                    <div class="pt-2">
                        {{ $roles->links() }}
                    </div>
                @endif
            </div>

            <aside class="space-y-6">
                <article class="app-panel rounded-[34px] p-6">
                    <p class="text-sm font-medium text-slate-500">Modelo de seguridad</p>
                    <h2 class="font-display mt-2 text-2xl font-semibold tracking-tight text-slate-950">Permisos por rol</h2>
                    <p class="mt-4 text-sm leading-7 text-slate-600">
                        Los permisos se asignan aqui. El usuario solo hereda lo que su rol tenga habilitado.
                    </p>
                </article>

                <article class="app-panel rounded-[34px] p-6">
                    <p class="text-sm font-medium text-slate-500">Siguiente paso</p>
                    <a href="{{ route('admin.permissions.index') }}" class="mt-4 block rounded-[24px] border border-slate-200 bg-white/85 p-4 transition hover:border-cyan-200">
                        <p class="text-sm font-semibold text-slate-900">Administrar permisos</p>
                        <p class="mt-2 text-sm leading-6 text-slate-600">Crea o ajusta las acciones disponibles para cada rol.</p>
                    </a>
                </article>
            </aside>
        </section>
    </div>
@endsection
