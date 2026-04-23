@php
    $editing = $role->exists;
    $selectedPermissionIds = collect(old('permission_ids', $editing ? $role->permissions->pluck('id')->all() : []))
        ->map(fn ($value) => (int) $value)
        ->all();
@endphp

<section class="grid gap-6 xl:grid-cols-[minmax(0,1.4fr)_360px]">
    <article class="app-panel rounded-[34px] p-6 sm:p-8">
        <div class="grid gap-6 md:grid-cols-2">
            <div>
                <label for="name" class="text-sm font-semibold text-slate-800">Nombre del rol</label>
                <input
                    id="name"
                    type="text"
                    name="name"
                    value="{{ old('name', $role->name) }}"
                    class="mt-2 w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-slate-900 outline-none transition focus:border-cyan-400"
                    placeholder="Ej. Supervisor de mantenimiento"
                >
                @error('name')
                    <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="code" class="text-sm font-semibold text-slate-800">Clave interna</label>
                <input
                    id="code"
                    type="text"
                    name="code"
                    value="{{ old('code', $role->code) }}"
                    class="mt-2 w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-slate-900 outline-none transition focus:border-cyan-400 disabled:cursor-not-allowed disabled:bg-slate-100"
                    placeholder="Se genera automaticamente si lo dejas vacio"
                    @disabled($editing && $role->is_system)
                >
                @if ($editing && $role->is_system)
                    <p class="mt-2 text-xs uppercase tracking-[0.2em] text-slate-400">La clave del rol de sistema queda protegida.</p>
                @endif
                @error('code')
                    <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="md:col-span-2">
                <label for="description" class="text-sm font-semibold text-slate-800">Descripcion funcional</label>
                <textarea
                    id="description"
                    name="description"
                    rows="4"
                    class="mt-2 w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-slate-900 outline-none transition focus:border-cyan-400"
                    placeholder="Describe cuando debe usarse este rol y sobre que modulo opera."
                >{{ old('description', $role->description) }}</textarea>
                @error('description')
                    <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="mt-8">
            <div class="flex flex-wrap items-center justify-between gap-3">
                <div>
                    <p class="text-sm font-medium text-slate-500">Permisos del rol</p>
                    <h2 class="font-display mt-2 text-2xl font-semibold tracking-tight text-slate-950">Acciones autorizadas</h2>
                </div>

                <span class="rounded-full border border-slate-200 bg-white/80 px-4 py-2 text-sm font-medium text-slate-600 shadow-sm">
                    {{ count($selectedPermissionIds) }} seleccionados
                </span>
            </div>

            <div class="mt-6 space-y-5">
                @forelse ($permissionGroups as $module => $permissions)
                    <section class="rounded-[28px] border border-slate-200 bg-white/85 p-5">
                        <div class="flex flex-wrap items-center justify-between gap-3">
                            <div>
                                <p class="text-xs uppercase tracking-[0.24em] text-slate-400">Modulo</p>
                                <h3 class="mt-2 text-lg font-semibold text-slate-900">{{ \Illuminate\Support\Str::headline($module) }}</h3>
                            </div>

                            <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-600">
                                {{ $permissions->count() }} permisos
                            </span>
                        </div>

                        <div class="mt-5 grid gap-3 md:grid-cols-2">
                            @foreach ($permissions as $permission)
                                <label class="flex items-start gap-3 rounded-[22px] border border-slate-200 bg-white p-4 transition hover:border-cyan-200">
                                    <input
                                        type="checkbox"
                                        name="permission_ids[]"
                                        value="{{ $permission->id }}"
                                        class="mt-1 h-4 w-4 rounded border-slate-300 text-cyan-600 focus:ring-cyan-500"
                                        @checked(in_array($permission->id, $selectedPermissionIds, true))
                                    >

                                    <span class="min-w-0">
                                        <span class="block text-sm font-semibold text-slate-900">{{ $permission->name }}</span>
                                        <span class="mt-1 block text-xs uppercase tracking-[0.18em] text-slate-400">{{ $permission->code }}</span>
                                        <span class="mt-2 block text-sm leading-6 text-slate-600">{{ $permission->description ?: 'Sin descripcion operativa.' }}</span>
                                    </span>
                                </label>
                            @endforeach
                        </div>
                    </section>
                @empty
                    <div class="rounded-[28px] border border-amber-200 bg-amber-50/90 px-5 py-4 text-sm leading-6 text-amber-900">
                        No hay permisos disponibles. Primero crea permisos para poder asignarlos al rol.
                    </div>
                @endforelse
            </div>
            @error('permission_ids')
                <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
            @enderror
        </div>
    </article>

    <aside class="space-y-6">
        <article class="app-panel rounded-[34px] p-6">
            <p class="text-sm font-medium text-slate-500">Regla de negocio</p>
            <h2 class="font-display mt-2 text-2xl font-semibold tracking-tight text-slate-950">Herencia simple</h2>
            <p class="mt-4 text-sm leading-7 text-slate-600">
                Todo usuario con este rol heredara exactamente los permisos seleccionados en esta pantalla.
            </p>
        </article>

        @if ($permissionGroups->isEmpty())
            <article class="app-panel-soft rounded-[34px] border border-amber-200 bg-amber-50/90 p-6">
                <p class="font-semibold text-amber-900">Antes de guardar, crea permisos</p>
                <p class="mt-3 text-sm leading-6 text-amber-800">Sin permisos definidos, el rol quedara vacio y no podra operar modulos protegidos.</p>
                <a href="{{ route('admin.permissions.create') }}" class="mt-5 inline-flex items-center justify-center rounded-2xl bg-amber-600 px-4 py-3 text-sm font-semibold text-white transition hover:bg-amber-700">
                    Crear permiso
                </a>
            </article>
        @endif

        <article class="app-panel rounded-[34px] p-6">
            <div class="flex flex-wrap gap-3">
                <button type="submit" class="inline-flex items-center justify-center rounded-2xl bg-slate-950 px-5 py-3 text-sm font-semibold text-white shadow-lg shadow-slate-950/15 transition hover:bg-cyan-700">
                    {{ $submitLabel }}
                </button>

                <a href="{{ route('admin.roles.index') }}" class="inline-flex items-center justify-center rounded-2xl border border-slate-200 bg-white px-5 py-3 text-sm font-semibold text-slate-700 transition hover:border-cyan-200 hover:text-slate-950">
                    Cancelar
                </a>
            </div>
        </article>
    </aside>
</section>
