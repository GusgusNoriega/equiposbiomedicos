@php
    $existingUnits = old('units');

    if ($existingUnits === null) {
        $existingUnits = $parameter->exists
            ? $parameter->units->map(fn ($unit) => [
                'id' => $unit->id,
                'name' => $unit->name,
                'symbol' => $unit->symbol,
                'code' => $unit->code,
                'sort_order' => $unit->sort_order,
            ])->all()
            : [];
    }

    $unitRows = count($existingUnits) > 0
        ? array_values($existingUnits)
        : [['id' => '', 'name' => '', 'symbol' => '', 'code' => '', 'sort_order' => 10]];
@endphp

<section class="grid gap-6 xl:grid-cols-[minmax(0,1.25fr)_360px]">
    <article class="space-y-6">
        <div class="app-panel rounded-[34px] p-6 sm:p-8">
            <div class="grid gap-6 md:grid-cols-2">
                <div class="md:col-span-2">
                    <label for="name" class="text-sm font-semibold text-slate-800">Nombre del parametro</label>
                    <input
                        id="name"
                        type="text"
                        name="name"
                        value="{{ old('name', $parameter->name) }}"
                        class="mt-2 w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-slate-900 outline-none transition focus:border-cyan-400"
                        placeholder="Ej. Peso, autonomia o potencia"
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
                        value="{{ old('code', $parameter->code) }}"
                        class="mt-2 w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-slate-900 outline-none transition focus:border-cyan-400"
                        placeholder="Se genera automaticamente si lo dejas vacio"
                    >
                    @error('code')
                        <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="value_type" class="text-sm font-semibold text-slate-800">Tipo de valor</label>
                    <select
                        id="value_type"
                        name="value_type"
                        class="mt-2 w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-slate-900 outline-none transition focus:border-cyan-400"
                    >
                        <option value="text" @selected(old('value_type', $parameter->value_type ?: 'text') === 'text')>Texto</option>
                        <option value="number" @selected(old('value_type', $parameter->value_type) === 'number')>Numerico</option>
                    </select>
                    @error('value_type')
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
                        placeholder="Explica que representa el parametro y como deberia capturarse."
                    >{{ old('description', $parameter->description) }}</textarea>
                    @error('description')
                        <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="sort_order" class="text-sm font-semibold text-slate-800">Orden</label>
                    <input
                        id="sort_order"
                        type="number"
                        name="sort_order"
                        value="{{ old('sort_order', $parameter->sort_order ?? 0) }}"
                        class="mt-2 w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-slate-900 outline-none transition focus:border-cyan-400"
                        min="0"
                    >
                    @error('sort_order')
                        <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid gap-4">
                    <label class="flex items-center gap-3 rounded-[24px] border border-slate-200 bg-white/85 px-4 py-4">
                        <input
                            type="checkbox"
                            name="is_filterable"
                            value="1"
                            class="h-4 w-4 rounded border-slate-300 text-cyan-600 focus:ring-cyan-500"
                            @checked(old('is_filterable', $parameter->exists ? $parameter->is_filterable : true))
                        >
                        <span>
                            <span class="block text-sm font-semibold text-slate-900">Usar en filtros</span>
                            <span class="mt-1 block text-sm leading-6 text-slate-600">El parametro quedara preparado para filtros futuros.</span>
                        </span>
                    </label>

                    <label class="flex items-center gap-3 rounded-[24px] border border-slate-200 bg-white/85 px-4 py-4">
                        <input
                            type="checkbox"
                            name="is_active"
                            value="1"
                            class="h-4 w-4 rounded border-slate-300 text-cyan-600 focus:ring-cyan-500"
                            @checked(old('is_active', $parameter->exists ? $parameter->is_active : true))
                        >
                        <span>
                            <span class="block text-sm font-semibold text-slate-900">Parametro activo</span>
                            <span class="mt-1 block text-sm leading-6 text-slate-600">Los parametros inactivos no se ofreceran en nuevos formularios.</span>
                        </span>
                    </label>
                </div>
            </div>
        </div>

        <div class="app-panel rounded-[34px] p-6 sm:p-8">
            <div class="flex flex-wrap items-center justify-between gap-3">
                <div>
                    <p class="text-sm font-medium text-slate-500">Unidades disponibles</p>
                    <h2 class="font-display mt-2 text-2xl font-semibold tracking-tight text-slate-950">Medidas asociadas al parametro</h2>
                </div>

                <button
                    type="button"
                    class="inline-flex items-center justify-center rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm font-semibold text-slate-700 transition hover:border-cyan-200 hover:text-slate-950"
                    data-parameter-unit-add
                >
                    Agregar unidad
                </button>
            </div>

            <p class="mt-3 text-sm leading-7 text-slate-600">
                Define simbolos como `kg`, `cm`, `W`, `mAh` o deja el parametro sin unidad si solo manejara valores de texto.
            </p>

            <div class="mt-6 space-y-4" data-parameter-unit-list>
                @foreach ($unitRows as $index => $row)
                    <div class="rounded-[26px] border border-slate-200 bg-white/85 p-4" data-parameter-unit-row>
                        <input type="hidden" name="units[{{ $index }}][id]" value="{{ $row['id'] ?? '' }}">

                        <div class="grid gap-4 xl:grid-cols-[minmax(0,1fr)_180px_180px_120px_auto]">
                            <div>
                                <label class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-400">Nombre</label>
                                <input
                                    type="text"
                                    name="units[{{ $index }}][name]"
                                    value="{{ $row['name'] ?? '' }}"
                                    class="mt-2 w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-slate-900 outline-none transition focus:border-cyan-400"
                                    placeholder="Ej. Kilogramo"
                                >
                            </div>

                            <div>
                                <label class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-400">Simbolo</label>
                                <input
                                    type="text"
                                    name="units[{{ $index }}][symbol]"
                                    value="{{ $row['symbol'] ?? '' }}"
                                    class="mt-2 w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-slate-900 outline-none transition focus:border-cyan-400"
                                    placeholder="kg"
                                >
                            </div>

                            <div>
                                <label class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-400">Clave interna</label>
                                <input
                                    type="text"
                                    name="units[{{ $index }}][code]"
                                    value="{{ $row['code'] ?? '' }}"
                                    class="mt-2 w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-slate-900 outline-none transition focus:border-cyan-400"
                                    placeholder="Opcional"
                                >
                            </div>

                            <div>
                                <label class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-400">Orden</label>
                                <input
                                    type="number"
                                    name="units[{{ $index }}][sort_order]"
                                    value="{{ $row['sort_order'] ?? (($index + 1) * 10) }}"
                                    class="mt-2 w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-slate-900 outline-none transition focus:border-cyan-400"
                                    min="0"
                                >
                            </div>

                            <div class="flex items-end">
                                <button
                                    type="button"
                                    class="inline-flex w-full items-center justify-center rounded-2xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm font-semibold text-rose-700 transition hover:bg-rose-100"
                                    data-parameter-unit-remove
                                >
                                    Quitar
                                </button>
                            </div>
                        </div>

                        @error("units.$index.name")
                            <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
                        @enderror
                        @error("units.$index.symbol")
                            <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
                        @enderror
                        @error("units.$index.code")
                            <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>
                @endforeach
            </div>

            <template data-parameter-unit-template>
                <div class="rounded-[26px] border border-slate-200 bg-white/85 p-4" data-parameter-unit-row>
                    <input type="hidden" name="__ID_NAME__" value="">

                    <div class="grid gap-4 xl:grid-cols-[minmax(0,1fr)_180px_180px_120px_auto]">
                        <div>
                            <label class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-400">Nombre</label>
                            <input type="text" name="__NAME_NAME__" class="mt-2 w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-slate-900 outline-none transition focus:border-cyan-400" placeholder="Ej. Kilogramo">
                        </div>

                        <div>
                            <label class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-400">Simbolo</label>
                            <input type="text" name="__SYMBOL_NAME__" class="mt-2 w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-slate-900 outline-none transition focus:border-cyan-400" placeholder="kg">
                        </div>

                        <div>
                            <label class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-400">Clave interna</label>
                            <input type="text" name="__CODE_NAME__" class="mt-2 w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-slate-900 outline-none transition focus:border-cyan-400" placeholder="Opcional">
                        </div>

                        <div>
                            <label class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-400">Orden</label>
                            <input type="number" name="__SORT_NAME__" value="10" min="0" class="mt-2 w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-slate-900 outline-none transition focus:border-cyan-400">
                        </div>

                        <div class="flex items-end">
                            <button type="button" class="inline-flex w-full items-center justify-center rounded-2xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm font-semibold text-rose-700 transition hover:bg-rose-100" data-parameter-unit-remove>
                                Quitar
                            </button>
                        </div>
                    </div>
                </div>
            </template>

            @error('units')
                <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
            @enderror
        </div>
    </article>

    <aside class="space-y-6">
        <article class="app-panel rounded-[34px] p-6">
            <p class="text-sm font-medium text-slate-500">Criterio</p>
            <h2 class="font-display mt-2 text-2xl font-semibold tracking-tight text-slate-950">Pensado para filtros</h2>
            <p class="mt-4 text-sm leading-7 text-slate-600">
                Si el dato se usara despues para comparar productos, crear reportes o filtrar por rangos, conviertelo en parametro filtrable.
            </p>
        </article>

        <article class="app-panel rounded-[34px] p-6">
            <p class="text-sm font-medium text-slate-500">Buenas practicas</p>
            <ul class="mt-4 space-y-3 text-sm leading-6 text-slate-600">
                <li>Usa tipo numerico para capacidad, rango, potencia o peso.</li>
                <li>Define unidades cortas y consistentes para evitar duplicados.</li>
                <li>Reserva el tipo texto para clasificaciones o atributos no medibles.</li>
            </ul>
        </article>

        <article class="app-panel rounded-[34px] p-6">
            <div class="flex flex-wrap gap-3">
                <button type="submit" class="inline-flex items-center justify-center rounded-2xl bg-slate-950 px-5 py-3 text-sm font-semibold text-white shadow-lg shadow-slate-950/15 transition hover:bg-cyan-700">
                    {{ $submitLabel }}
                </button>

                <a href="{{ route('admin.product-parameters.index') }}" class="inline-flex items-center justify-center rounded-2xl border border-slate-200 bg-white px-5 py-3 text-sm font-semibold text-slate-700 transition hover:border-cyan-200 hover:text-slate-950">
                    Cancelar
                </a>
            </div>
        </article>
    </aside>
</section>
