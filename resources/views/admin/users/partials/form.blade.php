@php
    $editing = $user->exists;
@endphp

<section class="grid gap-6 xl:grid-cols-[minmax(0,1.35fr)_360px]">
    <article class="app-panel rounded-[34px] p-6 sm:p-8">
        <div class="grid gap-6 md:grid-cols-2">
            <div class="md:col-span-2">
                <label for="name" class="text-sm font-semibold text-slate-800">Nombre completo</label>
                <input
                    id="name"
                    type="text"
                    name="name"
                    value="{{ old('name', $user->name) }}"
                    class="mt-2 w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-slate-900 outline-none transition focus:border-cyan-400"
                    placeholder="Ej. Maria Fernanda Gomez"
                >
                @error('name')
                    <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="email" class="text-sm font-semibold text-slate-800">Correo electronico</label>
                <input
                    id="email"
                    type="email"
                    name="email"
                    value="{{ old('email', $user->email) }}"
                    class="mt-2 w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-slate-900 outline-none transition focus:border-cyan-400"
                    placeholder="usuario@hospital.com"
                >
                @error('email')
                    <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="role_id" class="text-sm font-semibold text-slate-800">Rol asignado</label>
                <select
                    id="role_id"
                    name="role_id"
                    class="mt-2 w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-slate-900 outline-none transition focus:border-cyan-400"
                >
                    <option value="">Selecciona un rol</option>
                    @foreach ($roles as $role)
                        <option value="{{ $role->id }}" @selected(old('role_id', $user->role_id) == $role->id)>
                            {{ $role->name }}
                        </option>
                    @endforeach
                </select>
                @error('role_id')
                    <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password" class="text-sm font-semibold text-slate-800">
                    {{ $editing ? 'Nueva contrasena' : 'Contrasena' }}
                </label>
                <input
                    id="password"
                    type="password"
                    name="password"
                    class="mt-2 w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-slate-900 outline-none transition focus:border-cyan-400"
                    placeholder="{{ $editing ? 'Deja en blanco para conservar la actual' : 'Minimo 8 caracteres' }}"
                >
                @error('password')
                    <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password_confirmation" class="text-sm font-semibold text-slate-800">Confirmar contrasena</label>
                <input
                    id="password_confirmation"
                    type="password"
                    name="password_confirmation"
                    class="mt-2 w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-slate-900 outline-none transition focus:border-cyan-400"
                    placeholder="Repite la contrasena"
                >
            </div>
        </div>
    </article>

    <aside class="space-y-6">
        <article class="app-panel rounded-[34px] p-6">
            <p class="text-sm font-medium text-slate-500">Contexto</p>
            <h2 class="font-display mt-2 text-2xl font-semibold tracking-tight text-slate-950">{{ $editing ? 'Actualizacion segura' : 'Alta de cuenta' }}</h2>
            <p class="mt-4 text-sm leading-7 text-slate-600">
                {{ $editing ? 'Puedes cambiar datos basicos y reasignar el rol sin tocar la contrasena actual.' : 'El usuario quedara ligado a un rol desde el momento de su creacion.' }}
            </p>
        </article>

        @if ($roles->isEmpty())
            <article class="app-panel-soft rounded-[34px] border border-amber-200 bg-amber-50/90 p-6">
                <p class="font-semibold text-amber-900">Primero debes crear un rol</p>
                <p class="mt-3 text-sm leading-6 text-amber-800">No se puede guardar un usuario sin un rol disponible.</p>
                <a href="{{ route('admin.roles.create') }}" class="mt-5 inline-flex items-center justify-center rounded-2xl bg-amber-600 px-4 py-3 text-sm font-semibold text-white transition hover:bg-amber-700">
                    Crear rol
                </a>
            </article>
        @endif

        <article class="app-panel rounded-[34px] p-6">
            <div class="flex flex-wrap gap-3">
                <button
                    type="submit"
                    class="inline-flex items-center justify-center rounded-2xl bg-slate-950 px-5 py-3 text-sm font-semibold text-white shadow-lg shadow-slate-950/15 transition hover:bg-cyan-700"
                    @disabled($roles->isEmpty())
                >
                    {{ $submitLabel }}
                </button>

                <a href="{{ route('admin.users.index') }}" class="inline-flex items-center justify-center rounded-2xl border border-slate-200 bg-white px-5 py-3 text-sm font-semibold text-slate-700 transition hover:border-cyan-200 hover:text-slate-950">
                    Cancelar
                </a>
            </div>
        </article>
    </aside>
</section>
