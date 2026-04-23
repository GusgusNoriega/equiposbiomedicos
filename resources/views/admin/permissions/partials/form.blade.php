<section class="grid gap-6 xl:grid-cols-[minmax(0,1.2fr)_360px]">
    <article class="app-panel rounded-[34px] p-6 sm:p-8">
        <div class="grid gap-6">
            <div>
                <label for="name" class="text-sm font-semibold text-slate-800">Nombre del permiso</label>
                <input
                    id="name"
                    type="text"
                    name="name"
                    value="{{ old('name', $permission->name) }}"
                    class="mt-2 w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-slate-900 outline-none transition focus:border-cyan-400"
                    placeholder="Ej. Aprobar mantenimiento"
                >
                @error('name')
                    <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid gap-6 md:grid-cols-2">
                <div>
                    <label for="code" class="text-sm font-semibold text-slate-800">Clave interna</label>
                    <input
                        id="code"
                        type="text"
                        name="code"
                        value="{{ old('code', $permission->code) }}"
                        class="mt-2 w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-slate-900 outline-none transition focus:border-cyan-400"
                        placeholder="Se genera automaticamente si lo dejas vacio"
                    >
                    @error('code')
                        <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="module" class="text-sm font-semibold text-slate-800">Modulo</label>
                    <input
                        id="module"
                        type="text"
                        name="module"
                        value="{{ old('module', $permission->module) }}"
                        class="mt-2 w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-slate-900 outline-none transition focus:border-cyan-400"
                        placeholder="Ej. usuarios"
                    >
                    @error('module')
                        <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div>
                <label for="description" class="text-sm font-semibold text-slate-800">Descripcion</label>
                <textarea
                    id="description"
                    name="description"
                    rows="5"
                    class="mt-2 w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-slate-900 outline-none transition focus:border-cyan-400"
                    placeholder="Explica cuando debe usarse este permiso dentro del administrador."
                >{{ old('description', $permission->description) }}</textarea>
                @error('description')
                    <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
                @enderror
            </div>
        </div>
    </article>

    <aside class="space-y-6">
        <article class="app-panel rounded-[34px] p-6">
            <p class="text-sm font-medium text-slate-500">Buena practica</p>
            <h2 class="font-display mt-2 text-2xl font-semibold tracking-tight text-slate-950">Permisos concretos</h2>
            <p class="mt-4 text-sm leading-7 text-slate-600">
                Define acciones atomicas y claras para que los roles puedan combinarse sin ambiguedades.
            </p>
        </article>

        <article class="app-panel rounded-[34px] p-6">
            <div class="flex flex-wrap gap-3">
                <button type="submit" class="inline-flex items-center justify-center rounded-2xl bg-slate-950 px-5 py-3 text-sm font-semibold text-white shadow-lg shadow-slate-950/15 transition hover:bg-cyan-700">
                    {{ $submitLabel }}
                </button>

                <a href="{{ route('admin.permissions.index') }}" class="inline-flex items-center justify-center rounded-2xl border border-slate-200 bg-white px-5 py-3 text-sm font-semibold text-slate-700 transition hover:border-cyan-200 hover:text-slate-950">
                    Cancelar
                </a>
            </div>
        </article>
    </aside>
</section>
