@php
    $existingRows = old('specifications');
    $existingImages = $product->exists ? $product->images : collect();
    $existingAttachments = $product->exists ? $product->attachments : collect();
    $availableBrands = $brands ?? collect();
    $availableParameters = $parameters ?? collect();

    if ($existingRows === null) {
        $existingRows = $product->exists
            ? $product->specifications->map(fn ($specification) => [
                'label' => $specification->label,
                'value' => $specification->value,
                'unit' => $specification->unit,
                'sort_order' => $specification->sort_order,
            ])->all()
            : [];
    }

    $specificationRows = count($existingRows) > 0
        ? array_values($existingRows)
        : [['label' => '', 'value' => '', 'unit' => '', 'sort_order' => 10]];

    $existingParameterRows = old('parameter_values');

    if ($existingParameterRows === null) {
        $existingParameterRows = $product->exists
            ? $product->parameterValues->map(fn ($parameterValue) => [
                'product_parameter_id' => $parameterValue->product_parameter_id,
                'product_parameter_unit_id' => $parameterValue->product_parameter_unit_id,
                'value' => $parameterValue->value_text,
                'sort_order' => $parameterValue->sort_order,
            ])->all()
            : [];
    }

    $parameterValueRows = count($existingParameterRows) > 0
        ? array_values($existingParameterRows)
        : ($availableParameters->isNotEmpty()
            ? [['product_parameter_id' => '', 'product_parameter_unit_id' => '', 'value' => '', 'sort_order' => 10]]
            : []);

    $parameterCatalog = $availableParameters
        ->map(fn ($parameter) => [
            'id' => $parameter->id,
            'name' => $parameter->name,
            'code' => $parameter->code,
            'value_type' => $parameter->value_type,
            'units' => $parameter->units
                ->map(fn ($unit) => [
                    'id' => $unit->id,
                    'name' => $unit->name,
                    'symbol' => $unit->symbol,
                ])
                ->values()
                ->all(),
        ])
        ->values();
@endphp

<section class="grid gap-6 xl:grid-cols-[minmax(0,1.45fr)_360px]">
    <article class="app-panel rounded-[34px] p-6 sm:p-8">
        <div class="grid gap-6 md:grid-cols-2">
            <div>
                <label for="category_id" class="text-sm font-semibold text-slate-800">Categoria</label>
                <select
                    id="category_id"
                    name="category_id"
                    class="mt-2 w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-slate-900 outline-none transition focus:border-cyan-400"
                >
                    <option value="">Selecciona una categoria</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" @selected(old('category_id', $product->category_id) == $category->id)>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
                @error('category_id')
                    <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="name" class="text-sm font-semibold text-slate-800">Nombre del producto</label>
                <input
                    id="name"
                    type="text"
                    name="name"
                    value="{{ old('name', $product->name) }}"
                    class="mt-2 w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-slate-900 outline-none transition focus:border-cyan-400"
                    placeholder="Ej. Monitor multiparametro"
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
                    value="{{ old('code', $product->code) }}"
                    class="mt-2 w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-slate-900 outline-none transition focus:border-cyan-400"
                    placeholder="Se genera automaticamente si lo dejas vacio"
                >
                @error('code')
                    <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="sku" class="text-sm font-semibold text-slate-800">SKU o referencia</label>
                <input
                    id="sku"
                    type="text"
                    name="sku"
                    value="{{ old('sku', $product->sku) }}"
                    class="mt-2 w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-slate-900 outline-none transition focus:border-cyan-400"
                    placeholder="Ej. BM-1000"
                >
                @error('sku')
                    <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="stock_actual" class="text-sm font-semibold text-slate-800">Stock actual</label>
                <input
                    id="stock_actual"
                    type="number"
                    name="stock_actual"
                    value="{{ old('stock_actual', $product->stock_actual ?? 0) }}"
                    class="mt-2 w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-slate-900 outline-none transition focus:border-cyan-400"
                    min="0"
                    step="1"
                    placeholder="0"
                >
                <p class="mt-2 text-xs uppercase tracking-[0.18em] text-slate-400">
                    Cantidad disponible actualmente para este producto.
                </p>
                @error('stock_actual')
                    <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <div class="flex items-center justify-between gap-3">
                    <label for="brand_id" class="text-sm font-semibold text-slate-800">Marca</label>
                    <a href="{{ route('admin.product-brands.create') }}" class="text-xs font-semibold uppercase tracking-[0.18em] text-cyan-700 transition hover:text-cyan-900">
                        Nueva marca
                    </a>
                </div>

                <select
                    id="brand_id"
                    name="brand_id"
                    class="mt-2 w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-slate-900 outline-none transition focus:border-cyan-400"
                >
                    <option value="">Selecciona una marca</option>
                    @foreach ($availableBrands as $brand)
                        <option value="{{ $brand->id }}" @selected(old('brand_id', $product->brand_id) == $brand->id)>
                            {{ $brand->name }} ({{ $brand->code }})
                        </option>
                    @endforeach
                </select>

                @if ($availableBrands->isEmpty())
                    <p class="mt-2 text-sm text-amber-700">
                        Aun no hay marcas creadas. Puedes registrar una desde el catalogo de marcas y luego volver a este formulario.
                    </p>
                @else
                    <p class="mt-2 text-xs uppercase tracking-[0.18em] text-slate-400">
                        La marca se administra desde su catalogo independiente para usar logos y filtros mas adelante.
                    </p>
                @endif

                @error('brand_id')
                    <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="model" class="text-sm font-semibold text-slate-800">Modelo</label>
                <input
                    id="model"
                    type="text"
                    name="model"
                    value="{{ old('model', $product->model) }}"
                    class="mt-2 w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-slate-900 outline-none transition focus:border-cyan-400"
                    placeholder="Ej. IntelliVue MX450"
                >
                @error('model')
                    <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="md:col-span-2">
                <label for="manufacturer" class="text-sm font-semibold text-slate-800">Fabricante</label>
                <input
                    id="manufacturer"
                    type="text"
                    name="manufacturer"
                    value="{{ old('manufacturer', $product->manufacturer) }}"
                    class="mt-2 w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-slate-900 outline-none transition focus:border-cyan-400"
                    placeholder="Ej. Philips Healthcare"
                >
                @error('manufacturer')
                    <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="md:col-span-2">
                <label for="short_description" class="text-sm font-semibold text-slate-800">Descripcion corta</label>
                <input
                    id="short_description"
                    type="text"
                    name="short_description"
                    value="{{ old('short_description', $product->short_description) }}"
                    class="mt-2 w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-slate-900 outline-none transition focus:border-cyan-400"
                    placeholder="Resumen ejecutivo del producto para listados y fichas rapidas"
                >
                @error('short_description')
                    <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="md:col-span-2">
                <label for="description" class="text-sm font-semibold text-slate-800">Descripcion ampliada</label>
                <textarea
                    id="description"
                    name="description"
                    rows="6"
                    class="mt-2 w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-slate-900 outline-none transition focus:border-cyan-400"
                    placeholder="Incluye uso clinico, caracteristicas funcionales y notas operativas del producto."
                >{{ old('description', $product->description) }}</textarea>
                @error('description')
                    <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="mt-8 grid gap-6 xl:grid-cols-[minmax(0,1fr)_minmax(0,1fr)]">
            <section class="rounded-[28px] border border-slate-200 bg-white/85 p-5">
                <div class="flex flex-wrap items-center justify-between gap-3">
                    <div>
                        <p class="text-sm font-medium text-slate-500">Imagen destacada</p>
                        <h2 class="font-display mt-2 text-2xl font-semibold tracking-tight text-slate-950">Portada del producto</h2>
                    </div>
                </div>

                @if ($product->hasFeaturedImage())
                    <div class="mt-5 overflow-hidden rounded-[24px] border border-slate-200 bg-slate-50">
                        <img
                            src="{{ route('admin.products.featured-image', $product) }}"
                            alt="{{ $product->name }}"
                            class="h-64 w-full object-cover"
                        >
                    </div>

                    <label class="mt-4 flex items-center gap-3 rounded-[22px] border border-rose-200 bg-rose-50/80 px-4 py-4">
                        <input
                            type="checkbox"
                            name="remove_featured_image"
                            value="1"
                            class="h-4 w-4 rounded border-rose-300 text-rose-600 focus:ring-rose-500"
                            @checked(old('remove_featured_image'))
                        >
                        <span class="text-sm leading-6 text-rose-800">
                            Eliminar imagen destacada actual
                        </span>
                    </label>
                @endif

                <div class="mt-5">
                    <label for="featured_image" class="text-sm font-semibold text-slate-800">Subir imagen destacada</label>
                    <input
                        id="featured_image"
                        type="file"
                        name="featured_image"
                        accept="image/*"
                        class="mt-2 block w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-slate-900"
                    >
                    <p class="mt-2 text-xs uppercase tracking-[0.18em] text-slate-400">Formatos imagen, maximo 5 MB.</p>
                    @error('featured_image')
                        <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
                    @enderror
                </div>
            </section>

            <section class="rounded-[28px] border border-slate-200 bg-white/85 p-5">
                <div class="flex flex-wrap items-center justify-between gap-3">
                    <div>
                        <p class="text-sm font-medium text-slate-500">Galeria</p>
                        <h2 class="font-display mt-2 text-2xl font-semibold tracking-tight text-slate-950">Imagenes adicionales</h2>
                    </div>
                    <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-600">
                        {{ $existingImages->count() }} actuales
                    </span>
                </div>

                @if ($existingImages->isNotEmpty())
                    <div class="mt-5 grid gap-4 sm:grid-cols-2">
                        @foreach ($existingImages as $image)
                            <label class="rounded-[22px] border border-slate-200 bg-white p-3">
                                <img
                                    src="{{ route('admin.product-images.show', $image) }}"
                                    alt="{{ $image->original_name }}"
                                    class="h-36 w-full rounded-[18px] object-cover"
                                >
                                <div class="mt-3 flex items-start gap-3">
                                    <input
                                        type="checkbox"
                                        name="remove_image_ids[]"
                                        value="{{ $image->id }}"
                                        class="mt-1 h-4 w-4 rounded border-rose-300 text-rose-600 focus:ring-rose-500"
                                    >
                                    <span class="min-w-0 text-sm leading-6 text-slate-700">
                                        Eliminar {{ $image->original_name ?: 'imagen' }}
                                    </span>
                                </div>
                            </label>
                        @endforeach
                    </div>
                @endif

                <div class="mt-5">
                    <label for="gallery_images" class="text-sm font-semibold text-slate-800">Subir imagenes de galeria</label>
                    <input
                        id="gallery_images"
                        type="file"
                        name="gallery_images[]"
                        accept="image/*"
                        multiple
                        class="mt-2 block w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-slate-900"
                    >
                    <p class="mt-2 text-xs uppercase tracking-[0.18em] text-slate-400">Multiples imagenes, maximo 5 MB por archivo.</p>
                    @error('gallery_images')
                        <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
                    @enderror
                    @error('gallery_images.*')
                        <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
                    @enderror
                </div>
            </section>
        </div>

        <div class="mt-8">
            <div class="flex flex-wrap items-center justify-between gap-3">
                <div>
                    <p class="text-sm font-medium text-slate-500">Especificaciones tecnicas</p>
                    <h2 class="font-display mt-2 text-2xl font-semibold tracking-tight text-slate-950">Ficha tecnica editable</h2>
                </div>

                <button
                    type="button"
                    class="inline-flex items-center justify-center rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm font-semibold text-slate-700 transition hover:border-cyan-200 hover:text-slate-950"
                    data-specification-add
                >
                    Agregar fila
                </button>
            </div>

            <div class="mt-6 space-y-4" data-specification-list>
                @foreach ($specificationRows as $index => $row)
                    <div class="rounded-[26px] border border-slate-200 bg-white/85 p-4" data-specification-row>
                        <div class="grid gap-4 md:grid-cols-[minmax(0,1.1fr)_minmax(0,1.1fr)_160px_120px_auto]">
                            <div>
                                <label class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-400">Parametro</label>
                                <input
                                    type="text"
                                    name="specifications[{{ $index }}][label]"
                                    value="{{ $row['label'] ?? '' }}"
                                    class="mt-2 w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-slate-900 outline-none transition focus:border-cyan-400"
                                    placeholder="Ej. Voltaje"
                                >
                            </div>

                            <div>
                                <label class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-400">Valor</label>
                                <input
                                    type="text"
                                    name="specifications[{{ $index }}][value]"
                                    value="{{ $row['value'] ?? '' }}"
                                    class="mt-2 w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-slate-900 outline-none transition focus:border-cyan-400"
                                    placeholder="Ej. 220"
                                >
                            </div>

                            <div>
                                <label class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-400">Unidad</label>
                                <input
                                    type="text"
                                    name="specifications[{{ $index }}][unit]"
                                    value="{{ $row['unit'] ?? '' }}"
                                    class="mt-2 w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-slate-900 outline-none transition focus:border-cyan-400"
                                    placeholder="V"
                                >
                            </div>

                            <div>
                                <label class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-400">Orden</label>
                                <input
                                    type="number"
                                    name="specifications[{{ $index }}][sort_order]"
                                    value="{{ $row['sort_order'] ?? (($index + 1) * 10) }}"
                                    class="mt-2 w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-slate-900 outline-none transition focus:border-cyan-400"
                                    min="0"
                                >
                            </div>

                            <div class="flex items-end">
                                <button
                                    type="button"
                                    class="inline-flex w-full items-center justify-center rounded-2xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm font-semibold text-rose-700 transition hover:bg-rose-100"
                                    data-specification-remove
                                >
                                    Quitar
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <template data-specification-template>
                <div class="rounded-[26px] border border-slate-200 bg-white/85 p-4" data-specification-row>
                    <div class="grid gap-4 md:grid-cols-[minmax(0,1.1fr)_minmax(0,1.1fr)_160px_120px_auto]">
                        <div>
                            <label class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-400">Parametro</label>
                            <input type="text" name="__NAME__" class="mt-2 w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-slate-900 outline-none transition focus:border-cyan-400" placeholder="Ej. Voltaje">
                        </div>

                        <div>
                            <label class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-400">Valor</label>
                            <input type="text" name="__VALUE__" class="mt-2 w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-slate-900 outline-none transition focus:border-cyan-400" placeholder="Ej. 220">
                        </div>

                        <div>
                            <label class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-400">Unidad</label>
                            <input type="text" name="__UNIT__" class="mt-2 w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-slate-900 outline-none transition focus:border-cyan-400" placeholder="V">
                        </div>

                        <div>
                            <label class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-400">Orden</label>
                            <input type="number" name="__SORT__" value="10" min="0" class="mt-2 w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-slate-900 outline-none transition focus:border-cyan-400">
                        </div>

                        <div class="flex items-end">
                            <button type="button" class="inline-flex w-full items-center justify-center rounded-2xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm font-semibold text-rose-700 transition hover:bg-rose-100" data-specification-remove>
                                Quitar
                            </button>
                        </div>
                    </div>
                </div>
            </template>

            @error('specifications')
                <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="mt-8 rounded-[28px] border border-slate-200 bg-white/85 p-5">
            <div class="flex flex-wrap items-center justify-between gap-3">
                <div>
                    <p class="text-sm font-medium text-slate-500">Parametros filtrables</p>
                    <h2 class="font-display mt-2 text-2xl font-semibold tracking-tight text-slate-950">Valores normalizados del producto</h2>
                </div>

                @if ($availableParameters->isNotEmpty())
                    <button
                        type="button"
                        class="inline-flex items-center justify-center rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm font-semibold text-slate-700 transition hover:border-cyan-200 hover:text-slate-950"
                        data-parameter-value-add
                    >
                        Agregar parametro
                    </button>
                @endif
            </div>

            <p class="mt-3 text-sm leading-7 text-slate-600">
                Usa este bloque para guardar valores reutilizables como peso, autonomia, capacidad o potencia. Estos datos quedaran listos para filtros futuros.
            </p>

            @if ($availableParameters->isEmpty())
                <div class="mt-5 rounded-[24px] border border-amber-200 bg-amber-50/90 p-5">
                    <p class="font-semibold text-amber-900">Todavia no has creado parametros filtrables</p>
                    <p class="mt-3 text-sm leading-6 text-amber-800">Primero define los parametros y sus unidades para luego asignarlos a cada producto.</p>
                    <a href="{{ route('admin.product-parameters.create') }}" class="mt-4 inline-flex items-center justify-center rounded-2xl bg-amber-600 px-4 py-3 text-sm font-semibold text-white transition hover:bg-amber-700">
                        Crear parametro
                    </a>
                </div>
            @else
                <script type="application/json" data-parameter-catalog>@json($parameterCatalog)</script>

                <div class="mt-6 space-y-4" data-parameter-value-list>
                    @foreach ($parameterValueRows as $index => $row)
                        @php
                            $selectedParameterId = filled($row['product_parameter_id'] ?? null) ? (int) $row['product_parameter_id'] : null;
                            $selectedUnitId = filled($row['product_parameter_unit_id'] ?? null) ? (int) $row['product_parameter_unit_id'] : null;
                            $selectedParameter = $availableParameters->firstWhere('id', $selectedParameterId);
                            $units = $selectedParameter?->units ?? collect();
                        @endphp

                        <div class="rounded-[26px] border border-slate-200 bg-white p-4" data-parameter-value-row>
                            <div class="grid gap-4 xl:grid-cols-[minmax(0,1.15fr)_minmax(0,0.95fr)_200px_120px_auto]">
                                <div>
                                    <label class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-400">Parametro</label>
                                    <select
                                        name="parameter_values[{{ $index }}][product_parameter_id]"
                                        class="mt-2 w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-slate-900 outline-none transition focus:border-cyan-400"
                                        data-parameter-select
                                    >
                                        <option value="">Selecciona un parametro</option>
                                        @foreach ($availableParameters as $parameter)
                                            <option value="{{ $parameter->id }}" data-value-type="{{ $parameter->value_type }}" @selected($selectedParameterId === $parameter->id)>
                                                {{ $parameter->name }} ({{ $parameter->code }})
                                            </option>
                                        @endforeach
                                    </select>
                                    <p class="mt-2 text-xs uppercase tracking-[0.18em] text-slate-400" data-parameter-type-hint>
                                        @if ($selectedParameter)
                                            {{ $selectedParameter->value_type === 'number' ? 'Valor numerico recomendado para filtros por rango.' : 'Valor de texto libre para filtros exactos.' }}
                                        @else
                                            Selecciona un parametro para cargar sus unidades.
                                        @endif
                                    </p>
                                    @error("parameter_values.$index.product_parameter_id")
                                        <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-400">Valor</label>
                                    <input
                                        type="text"
                                        name="parameter_values[{{ $index }}][value]"
                                        value="{{ $row['value'] ?? '' }}"
                                        class="mt-2 w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-slate-900 outline-none transition focus:border-cyan-400"
                                        placeholder="Ej. 12.5 o Portatil"
                                    >
                                    @error("parameter_values.$index.value")
                                        <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-400">Unidad</label>
                                    <select
                                        name="parameter_values[{{ $index }}][product_parameter_unit_id]"
                                        class="mt-2 w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-slate-900 outline-none transition focus:border-cyan-400"
                                        data-parameter-unit-select
                                    >
                                        <option value="">Sin unidad</option>
                                        @foreach ($units as $unit)
                                            <option value="{{ $unit->id }}" @selected($selectedUnitId === $unit->id)>
                                                {{ $unit->name }}@if($unit->symbol) ({{ $unit->symbol }})@endif
                                            </option>
                                        @endforeach
                                    </select>
                                    @error("parameter_values.$index.product_parameter_unit_id")
                                        <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-400">Orden</label>
                                    <input
                                        type="number"
                                        name="parameter_values[{{ $index }}][sort_order]"
                                        value="{{ $row['sort_order'] ?? (($index + 1) * 10) }}"
                                        class="mt-2 w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-slate-900 outline-none transition focus:border-cyan-400"
                                        min="0"
                                    >
                                </div>

                                <div class="flex items-end">
                                    <button
                                        type="button"
                                        class="inline-flex w-full items-center justify-center rounded-2xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm font-semibold text-rose-700 transition hover:bg-rose-100"
                                        data-parameter-value-remove
                                    >
                                        Quitar
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <template data-parameter-value-template>
                    <div class="rounded-[26px] border border-slate-200 bg-white p-4" data-parameter-value-row>
                        <div class="grid gap-4 xl:grid-cols-[minmax(0,1.15fr)_minmax(0,0.95fr)_200px_120px_auto]">
                            <div>
                                <label class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-400">Parametro</label>
                                <select name="__PARAMETER_NAME__" class="mt-2 w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-slate-900 outline-none transition focus:border-cyan-400" data-parameter-select>
                                    <option value="">Selecciona un parametro</option>
                                </select>
                                <p class="mt-2 text-xs uppercase tracking-[0.18em] text-slate-400" data-parameter-type-hint>Selecciona un parametro para cargar sus unidades.</p>
                            </div>

                            <div>
                                <label class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-400">Valor</label>
                                <input type="text" name="__VALUE_NAME__" class="mt-2 w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-slate-900 outline-none transition focus:border-cyan-400" placeholder="Ej. 12.5 o Portatil">
                            </div>

                            <div>
                                <label class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-400">Unidad</label>
                                <select name="__UNIT_NAME__" class="mt-2 w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-slate-900 outline-none transition focus:border-cyan-400" data-parameter-unit-select>
                                    <option value="">Sin unidad</option>
                                </select>
                            </div>

                            <div>
                                <label class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-400">Orden</label>
                                <input type="number" name="__SORT_NAME__" value="10" min="0" class="mt-2 w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-slate-900 outline-none transition focus:border-cyan-400">
                            </div>

                            <div class="flex items-end">
                                <button type="button" class="inline-flex w-full items-center justify-center rounded-2xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm font-semibold text-rose-700 transition hover:bg-rose-100" data-parameter-value-remove>
                                    Quitar
                                </button>
                            </div>
                        </div>
                    </div>
                </template>
            @endif

            @error('parameter_values')
                <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="mt-8 rounded-[28px] border border-slate-200 bg-white/85 p-5">
            <div class="flex flex-wrap items-center justify-between gap-3">
                <div>
                    <p class="text-sm font-medium text-slate-500">Archivos adjuntos</p>
                    <h2 class="font-display mt-2 text-2xl font-semibold tracking-tight text-slate-950">Documentos del producto</h2>
                </div>
                <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-600">
                    {{ $existingAttachments->count() }} actuales
                </span>
            </div>

            @if ($existingAttachments->isNotEmpty())
                <div class="mt-5 space-y-3">
                    @foreach ($existingAttachments as $attachment)
                        <div class="flex flex-wrap items-center justify-between gap-3 rounded-[22px] border border-slate-200 bg-white p-4">
                            <div class="min-w-0">
                                <a
                                    href="{{ route('admin.product-attachments.download', $attachment) }}"
                                    class="text-sm font-semibold text-slate-900 underline decoration-slate-300 underline-offset-4"
                                >
                                    {{ $attachment->original_name ?: 'Archivo adjunto' }}
                                </a>
                                <p class="mt-1 text-xs uppercase tracking-[0.18em] text-slate-400">
                                    {{ $attachment->mime_type ?: 'archivo' }}
                                </p>
                            </div>

                            <label class="flex items-center gap-3 text-sm text-rose-700">
                                <input
                                    type="checkbox"
                                    name="remove_attachment_ids[]"
                                    value="{{ $attachment->id }}"
                                    class="h-4 w-4 rounded border-rose-300 text-rose-600 focus:ring-rose-500"
                                >
                                Eliminar
                            </label>
                        </div>
                    @endforeach
                </div>
            @endif

            <div class="mt-5">
                <label for="attachments_files" class="text-sm font-semibold text-slate-800">Adjuntar archivos</label>
                <input
                    id="attachments_files"
                    type="file"
                    name="attachments_files[]"
                    multiple
                    class="mt-2 block w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-slate-900"
                >
                <p class="mt-2 text-xs uppercase tracking-[0.18em] text-slate-400">PDF, hojas tecnicas, manuales o documentos comerciales. Maximo 10 MB por archivo.</p>
                @error('attachments_files')
                    <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
                @enderror
                @error('attachments_files.*')
                    <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
                @enderror
            </div>
        </div>
    </article>

    <aside class="space-y-6">
        <article class="app-panel rounded-[34px] p-6">
            <p class="text-sm font-medium text-slate-500">Estado del producto</p>
            <label class="mt-4 flex items-center gap-3 rounded-[24px] border border-slate-200 bg-white/85 px-4 py-4">
                <input
                    type="checkbox"
                    name="is_active"
                    value="1"
                    class="h-4 w-4 rounded border-slate-300 text-cyan-600 focus:ring-cyan-500"
                    @checked(old('is_active', $product->exists ? $product->is_active : true))
                >
                <span>
                    <span class="block text-sm font-semibold text-slate-900">Producto activo</span>
                    <span class="mt-1 block text-sm leading-6 text-slate-600">Los productos inactivos permanecen historicos pero no deberian usarse para nuevas operaciones.</span>
                </span>
            </label>
        </article>

        @if ($categories->isEmpty())
            <article class="app-panel-soft rounded-[34px] border border-amber-200 bg-amber-50/90 p-6">
                <p class="font-semibold text-amber-900">Primero debes crear una categoria</p>
                <p class="mt-3 text-sm leading-6 text-amber-800">El producto necesita una categoria para quedar correctamente clasificado.</p>
                <a href="{{ route('admin.product-categories.create') }}" class="mt-5 inline-flex items-center justify-center rounded-2xl bg-amber-600 px-4 py-3 text-sm font-semibold text-white transition hover:bg-amber-700">
                    Crear categoria
                </a>
            </article>
        @endif

        @if ($availableBrands->isEmpty())
            <article class="app-panel-soft rounded-[34px] border border-cyan-200 bg-cyan-50/90 p-6">
                <p class="font-semibold text-cyan-900">Crea marcas para normalizar el catalogo</p>
                <p class="mt-3 text-sm leading-6 text-cyan-800">Las marcas con logo permitiran filtrar productos y mantener una presentacion visual consistente.</p>
                <a href="{{ route('admin.product-brands.create') }}" class="mt-5 inline-flex items-center justify-center rounded-2xl bg-cyan-700 px-4 py-3 text-sm font-semibold text-white transition hover:bg-cyan-800">
                    Crear marca
                </a>
            </article>
        @endif

        <article class="app-panel rounded-[34px] p-6">
            <p class="text-sm font-medium text-slate-500">Regla del formulario</p>
            <h2 class="font-display mt-2 text-2xl font-semibold tracking-tight text-slate-950">Descripcion + datos filtrables</h2>
            <p class="mt-4 text-sm leading-7 text-slate-600">
                El producto queda documentado con textos descriptivos, ficha tecnica libre y parametros normalizados pensados para filtros futuros.
            </p>
        </article>

        <article class="app-panel rounded-[34px] p-6">
            <div class="flex flex-wrap gap-3">
                <button type="submit" class="inline-flex items-center justify-center rounded-2xl bg-slate-950 px-5 py-3 text-sm font-semibold text-white shadow-lg shadow-slate-950/15 transition hover:bg-cyan-700" @disabled($categories->isEmpty())>
                    {{ $submitLabel }}
                </button>

                <a href="{{ route('admin.products.index') }}" class="inline-flex items-center justify-center rounded-2xl border border-slate-200 bg-white px-5 py-3 text-sm font-semibold text-slate-700 transition hover:border-cyan-200 hover:text-slate-950">
                    Cancelar
                </a>
            </div>
        </article>
    </aside>
</section>
