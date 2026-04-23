<section class="grid gap-6 xl:grid-cols-[minmax(0,1.2fr)_360px]">
    <article class="app-panel rounded-[34px] p-6 sm:p-8">
        <div class="grid gap-6">
            <div>
                <label for="name" class="text-sm font-semibold text-slate-800">Nombre de la marca</label>
                <input
                    id="name"
                    type="text"
                    name="name"
                    value="{{ old('name', $productBrand->name) }}"
                    class="mt-2 w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-slate-900 outline-none transition focus:border-cyan-400"
                    placeholder="Ej. Philips"
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
                    value="{{ old('code', $productBrand->code) }}"
                    class="mt-2 w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-slate-900 outline-none transition focus:border-cyan-400"
                    placeholder="Se genera automaticamente si lo dejas vacio"
                >
                @error('code')
                    <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="description" class="text-sm font-semibold text-slate-800">Descripcion</label>
                <textarea
                    id="description"
                    name="description"
                    rows="5"
                    class="mt-2 w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-slate-900 outline-none transition focus:border-cyan-400"
                    placeholder="Describe la linea comercial, pais de origen o cualquier contexto util para esta marca."
                >{{ old('description', $productBrand->description) }}</textarea>
                @error('description')
                    <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="rounded-[28px] border border-slate-200 bg-white/85 p-5">
                <div class="flex flex-wrap items-center justify-between gap-3">
                    <div>
                        <p class="text-sm font-medium text-slate-500">Logo de la marca</p>
                        <h2 class="font-display mt-2 text-2xl font-semibold tracking-tight text-slate-950">Identidad visual</h2>
                    </div>
                </div>

                @if ($productBrand->hasLogo())
                    <div class="mt-5 flex h-36 items-center justify-center overflow-hidden rounded-[24px] border border-slate-200 bg-slate-50">
                        <img
                            src="{{ route('admin.product-brands.logo', $productBrand) }}"
                            alt="{{ $productBrand->name }}"
                            class="h-full w-full object-contain p-6"
                        >
                    </div>

                    <label class="mt-4 flex items-center gap-3 rounded-[22px] border border-rose-200 bg-rose-50/80 px-4 py-4">
                        <input
                            type="checkbox"
                            name="remove_logo"
                            value="1"
                            class="h-4 w-4 rounded border-rose-300 text-rose-600 focus:ring-rose-500"
                            @checked(old('remove_logo'))
                        >
                        <span class="text-sm leading-6 text-rose-800">
                            Eliminar logo actual
                        </span>
                    </label>
                @endif

                <div class="mt-5">
                    <label for="logo" class="text-sm font-semibold text-slate-800">Subir logo</label>
                    <input
                        id="logo"
                        type="file"
                        name="logo"
                        accept="image/*"
                        class="mt-2 block w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-slate-900"
                    >
                    <p class="mt-2 text-xs uppercase tracking-[0.18em] text-slate-400">Formatos imagen, maximo 5 MB.</p>
                    @error('logo')
                        <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <label class="flex items-center gap-3 rounded-[24px] border border-slate-200 bg-white/85 px-4 py-4">
                <input
                    type="checkbox"
                    name="is_active"
                    value="1"
                    class="h-4 w-4 rounded border-slate-300 text-cyan-600 focus:ring-cyan-500"
                    @checked(old('is_active', $productBrand->exists ? $productBrand->is_active : true))
                >
                <span>
                    <span class="block text-sm font-semibold text-slate-900">Marca activa</span>
                    <span class="mt-1 block text-sm leading-6 text-slate-600">Las marcas inactivas se conservan historicamente pero no deberian asignarse a nuevos productos.</span>
                </span>
            </label>
        </div>
    </article>

    <aside class="space-y-6">
        <article class="app-panel rounded-[34px] p-6">
            <p class="text-sm font-medium text-slate-500">Objetivo</p>
            <h2 class="font-display mt-2 text-2xl font-semibold tracking-tight text-slate-950">Catalogo consistente</h2>
            <p class="mt-4 text-sm leading-7 text-slate-600">
                Una marca normalizada evita duplicados y prepara el proyecto para filtros visuales y agrupaciones por fabricante.
            </p>
        </article>

        <article class="app-panel rounded-[34px] p-6">
            <div class="flex flex-wrap gap-3">
                <button type="submit" class="inline-flex items-center justify-center rounded-2xl bg-slate-950 px-5 py-3 text-sm font-semibold text-white shadow-lg shadow-slate-950/15 transition hover:bg-cyan-700">
                    {{ $submitLabel }}
                </button>

                <a href="{{ route('admin.product-brands.index') }}" class="inline-flex items-center justify-center rounded-2xl border border-slate-200 bg-white px-5 py-3 text-sm font-semibold text-slate-700 transition hover:border-cyan-200 hover:text-slate-950">
                    Cancelar
                </a>
            </div>
        </article>
    </aside>
</section>
