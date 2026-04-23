@extends('layouts.admin')

@section('title', 'Usuarios')
@section('page-kicker', 'Modulo de acceso')
@section('page-title', 'Usuarios del administrador')
@section('page-description', 'Administra cuentas operativas, asigna un rol por usuario y manten el acceso alineado con la estructura del area biomedica.')

@section('header-actions')
    <a href="{{ route('admin.users.create') }}" class="inline-flex items-center justify-center rounded-2xl bg-slate-950 px-4 py-3 text-sm font-semibold text-white shadow-lg shadow-slate-950/15 transition hover:bg-cyan-700">
        Nuevo usuario
    </a>
@endsection

@section('content')
    <div class="space-y-6">
        @include('admin.access.tabs')

        <section class="grid gap-4 md:grid-cols-3">
            <article class="app-panel rounded-[28px] p-5">
                <p class="text-sm font-medium text-slate-500">Usuarios registrados</p>
                <p class="font-display mt-3 text-4xl font-semibold tracking-tight text-slate-950">{{ $stats['users'] }}</p>
                <p class="mt-2 text-sm leading-6 text-slate-600">Cuentas disponibles dentro del modulo administrativo.</p>
            </article>

            <article class="app-panel rounded-[28px] p-5">
                <p class="text-sm font-medium text-slate-500">Roles disponibles</p>
                <p class="font-display mt-3 text-4xl font-semibold tracking-tight text-slate-950">{{ $stats['roles'] }}</p>
                <p class="mt-2 text-sm leading-6 text-slate-600">Perfiles funcionales para equipos tecnicos, supervisores y administradores.</p>
            </article>

            <article class="app-panel rounded-[28px] p-5">
                <p class="text-sm font-medium text-slate-500">Usuarios sin rol</p>
                <p class="font-display mt-3 text-4xl font-semibold tracking-tight text-slate-950">{{ $stats['without_role'] }}</p>
                <p class="mt-2 text-sm leading-6 text-slate-600">El flujo del formulario exige rol, pero aqui puedes detectar cuentas antiguas.</p>
            </article>
        </section>

        <section class="grid gap-6 xl:grid-cols-[minmax(0,1.4fr)_360px]">
            <article class="app-panel rounded-[34px] p-6">
                <div class="flex flex-wrap items-center justify-between gap-3">
                    <div>
                        <p class="text-sm font-medium text-slate-500">Listado principal</p>
                        <h2 class="font-display mt-2 text-2xl font-semibold tracking-tight text-slate-950">Cuentas del sistema</h2>
                    </div>

                    <span class="rounded-full border border-slate-200 bg-white/80 px-4 py-2 text-sm font-medium text-slate-600 shadow-sm">
                        {{ $users->total() }} registros
                    </span>
                </div>

                <div class="mt-6 overflow-hidden rounded-[26px] border border-slate-200">
                    @if ($users->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-slate-200 bg-white/80 text-sm">
                                <thead class="bg-slate-950/4 text-left text-xs font-semibold uppercase tracking-[0.24em] text-slate-500">
                                    <tr>
                                        <th class="px-5 py-4">Usuario</th>
                                        <th class="px-5 py-4">Correo</th>
                                        <th class="px-5 py-4">Rol</th>
                                        <th class="px-5 py-4">Alta</th>
                                        <th class="px-5 py-4 text-right">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-200">
                                    @foreach ($users as $user)
                                        <tr class="align-top">
                                            <td class="px-5 py-4">
                                                <p class="font-semibold text-slate-900">{{ $user->name }}</p>
                                                <p class="mt-1 text-xs uppercase tracking-[0.2em] text-slate-400">ID {{ $user->id }}</p>
                                            </td>
                                            <td class="px-5 py-4 text-slate-600">{{ $user->email }}</td>
                                            <td class="px-5 py-4">
                                                @if ($user->role)
                                                    <span class="rounded-full bg-cyan-50 px-3 py-1 text-xs font-semibold text-cyan-700">
                                                        {{ $user->role->name }}
                                                    </span>
                                                @else
                                                    <span class="rounded-full bg-rose-50 px-3 py-1 text-xs font-semibold text-rose-700">
                                                        Sin rol
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="px-5 py-4 text-slate-600">{{ $user->created_at?->format('d/m/Y') }}</td>
                                            <td class="px-5 py-4">
                                                <div class="flex justify-end gap-2">
                                                    <a href="{{ route('admin.users.edit', $user) }}" class="rounded-2xl border border-slate-200 bg-white px-3 py-2 text-xs font-semibold uppercase tracking-[0.18em] text-slate-600 transition hover:border-cyan-200 hover:text-slate-900">
                                                        Editar
                                                    </a>

                                                    <form action="{{ route('admin.users.destroy', $user) }}" method="POST" onsubmit="return confirm('Se eliminara el usuario seleccionado.');">
                                                        @csrf
                                                        @method('DELETE')

                                                        <button type="submit" class="rounded-2xl border border-rose-200 bg-rose-50 px-3 py-2 text-xs font-semibold uppercase tracking-[0.18em] text-rose-700 transition hover:bg-rose-100">
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
                            <p class="font-display text-2xl font-semibold text-slate-900">Todavia no hay usuarios cargados</p>
                            <p class="mx-auto mt-3 max-w-xl text-sm leading-6 text-slate-600">Crea la primera cuenta administrativa y asignale un rol para activar el modulo de acceso.</p>
                            <a href="{{ route('admin.users.create') }}" class="mt-6 inline-flex items-center justify-center rounded-2xl bg-slate-950 px-4 py-3 text-sm font-semibold text-white transition hover:bg-cyan-700">
                                Crear primer usuario
                            </a>
                        </div>
                    @endif
                </div>

                @if ($users->hasPages())
                    <div class="mt-6">
                        {{ $users->links() }}
                    </div>
                @endif
            </article>

            <aside class="space-y-6">
                <article class="app-panel rounded-[34px] p-6">
                    <p class="text-sm font-medium text-slate-500">Regla del modulo</p>
                    <h2 class="font-display mt-2 text-2xl font-semibold tracking-tight text-slate-950">Un usuario, un rol</h2>
                    <p class="mt-4 text-sm leading-7 text-slate-600">
                        Cada cuenta queda asociada a un unico rol. Los permisos operativos no se asignan al usuario directamente, sino al rol que lo representa.
                    </p>
                </article>

                <article class="app-panel rounded-[34px] p-6">
                    <p class="text-sm font-medium text-slate-500">Accesos relacionados</p>
                    <div class="mt-5 space-y-3">
                        <a href="{{ route('admin.roles.index') }}" class="block rounded-[24px] border border-slate-200 bg-white/85 p-4 transition hover:border-cyan-200">
                            <p class="text-sm font-semibold text-slate-900">Ir a roles</p>
                            <p class="mt-2 text-sm leading-6 text-slate-600">Configura que puede hacer cada perfil dentro del administrador.</p>
                        </a>

                        <a href="{{ route('admin.permissions.index') }}" class="block rounded-[24px] border border-slate-200 bg-white/85 p-4 transition hover:border-cyan-200">
                            <p class="text-sm font-semibold text-slate-900">Ir a permisos</p>
                            <p class="mt-2 text-sm leading-6 text-slate-600">Define las acciones disponibles para el modulo de acceso.</p>
                        </a>
                    </div>
                </article>
            </aside>
        </section>
    </div>
@endsection
