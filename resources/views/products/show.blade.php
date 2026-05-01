@extends('layouts.site')

@php
    $productBrand = $product->productBrand?->name ?? $product->brand ?? 'Sin marca';
    $productCategory = $product->category?->name ?? 'Sin categoria';
    $simpleDescription = trim((string) ($product->short_description ?: strip_tags((string) $product->description)));
    $fullDescription = trim((string) $product->description);
    $metaDescription = $simpleDescription !== ''
        ? $simpleDescription
        : 'Ficha publica de producto biomedico con imagenes, datos tecnicos y documentos.';
    $stockLabel = number_format((int) $product->stock_actual) . ' ' . \Illuminate\Support\Str::plural('unidad', (int) $product->stock_actual);
    $detailRows = collect([
        ['label' => 'Categoria', 'value' => $productCategory],
        ['label' => 'Marca', 'value' => $productBrand],
        ['label' => 'Modelo', 'value' => $product->model],
        ['label' => 'Fabricante', 'value' => $product->manufacturer],
        ['label' => 'SKU', 'value' => $product->sku],
        ['label' => 'Codigo interno', 'value' => $product->code],
        ['label' => 'Inventario', 'value' => $stockLabel],
    ])->filter(fn (array $row): bool => filled($row['value']))->values();
    $formatFileSize = function (?int $bytes): string {
        if (! $bytes || $bytes < 1) {
            return 'Tamano no registrado';
        }

        if ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 1) . ' MB';
        }

        return number_format($bytes / 1024, 1) . ' KB';
    };
@endphp

@section('title', $product->name)
@section('company-name', $companyName)
@section('meta-description', \Illuminate\Support\Str::limit($metaDescription, 155))

@section('content')
    <section id="producto" class="px-4 pb-6 pt-2 sm:px-6 lg:px-8">
        <div class="site-shell">
            <div class="mb-5 flex flex-wrap items-center justify-between gap-3">
                <a href="{{ route('home') }}#portafolio" class="site-button-secondary">
                    <svg class="h-4 w-4" viewBox="0 0 20 20" fill="none" aria-hidden="true">
                        <path d="M12 5.5L7.5 10L12 14.5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                    Volver al catalogo
                </a>

                <span class="rounded-full border border-cyan-200/70 bg-cyan-50/90 px-4 py-2 text-xs font-semibold uppercase tracking-[0.24em] text-cyan-800">
                    Ficha de producto
                </span>
            </div>

            <div class="grid gap-6 xl:grid-cols-[minmax(0,0.96fr)_minmax(0,1.04fr)] xl:items-stretch">
                <section class="site-panel overflow-hidden rounded-[40px]" data-site-slider aria-label="Galeria de {{ $product->name }}">
                    <div class="relative min-h-[390px] sm:min-h-[500px]">
                        @foreach ($gallerySlides as $slide)
                            <article class="site-slide h-full" data-site-slide data-active="{{ $loop->first ? 'true' : 'false' }}">
                                <div class="absolute inset-0 bg-[linear-gradient(180deg,_rgba(240,249,255,0.96)_0%,_rgba(224,242,254,0.48)_42%,_rgba(255,255,255,0.94)_100%)]"></div>

                                <span class="absolute left-5 top-5 z-10 rounded-full bg-slate-950 px-4 py-2 text-[11px] font-semibold uppercase tracking-[0.22em] text-white shadow-lg shadow-slate-900/15">
                                    {{ $slide['label'] }}
                                </span>

                                <div class="relative flex min-h-[390px] items-center justify-center p-6 sm:min-h-[500px] sm:p-10">
                                    <img
                                        src="{{ $slide['src'] }}"
                                        alt="{{ $slide['alt'] }}"
                                        class="max-h-[330px] w-full object-contain object-center sm:max-h-[430px]"
                                    >
                                </div>
                            </article>
                        @endforeach
                    </div>

                    <div class="flex flex-wrap items-center justify-between gap-4 border-t border-slate-200/80 bg-white/82 px-5 py-4 sm:px-6">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-[0.24em] text-cyan-700/75">Galeria</p>
                            <p class="mt-1 text-sm text-slate-600">{{ $gallerySlides->count() }} {{ \Illuminate\Support\Str::plural('imagen', $gallerySlides->count()) }} disponible{{ $gallerySlides->count() === 1 ? '' : 's' }}</p>
                        </div>

                        @if ($gallerySlides->count() > 1)
                            <div class="flex items-center gap-4">
                                <div class="flex items-center gap-2">
                                    @foreach ($gallerySlides as $slide)
                                        <button
                                            type="button"
                                            class="site-slider-indicator"
                                            data-site-indicator="{{ $loop->index }}"
                                            data-active="{{ $loop->first ? 'true' : 'false' }}"
                                            aria-label="Ver imagen {{ $loop->iteration }}"
                                        ></button>
                                    @endforeach
                                </div>

                                <div class="flex items-center gap-2">
                                    <button type="button" class="site-slider-control" data-site-prev aria-label="Imagen anterior">
                                        <svg class="h-4 w-4" viewBox="0 0 20 20" fill="none" aria-hidden="true">
                                            <path d="M11.75 5.75L7.5 10L11.75 14.25" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                    </button>

                                    <button type="button" class="site-slider-control" data-site-next aria-label="Imagen siguiente">
                                        <svg class="h-4 w-4" viewBox="0 0 20 20" fill="none" aria-hidden="true">
                                            <path d="M8.25 5.75L12.5 10L8.25 14.25" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        @endif
                    </div>
                </section>

                <article class="site-panel rounded-[40px] p-6 sm:p-8 lg:p-10">
                    <span class="site-chip inline-flex items-center gap-2 rounded-full px-4 py-2 text-xs font-semibold uppercase tracking-[0.24em] text-cyan-700">
                        {{ $productCategory }}
                    </span>

                    <h1 class="font-display mt-6 text-3xl font-semibold tracking-tight text-slate-950 sm:text-4xl lg:text-5xl">
                        {{ $product->name }}
                    </h1>

                    <p class="mt-5 text-base leading-8 text-slate-600 sm:text-lg">
                        {{ $simpleDescription !== '' ? $simpleDescription : 'Producto registrado en el catalogo publico con ficha tecnica disponible para consulta.' }}
                    </p>

                    <div class="mt-8 grid gap-3 sm:grid-cols-2">
                        <div class="site-outline rounded-[26px] p-5">
                            <p class="text-xs font-semibold uppercase tracking-[0.24em] text-cyan-700/75">Marca</p>
                            <p class="mt-2 text-xl font-semibold text-slate-950">{{ $productBrand }}</p>
                        </div>

                        <div class="site-outline rounded-[26px] p-5">
                            <p class="text-xs font-semibold uppercase tracking-[0.24em] text-cyan-700/75">Inventario</p>
                            <p class="mt-2 text-xl font-semibold text-slate-950">{{ $stockLabel }}</p>
                        </div>

                        @if (filled($product->model))
                            <div class="site-outline rounded-[26px] p-5">
                                <p class="text-xs font-semibold uppercase tracking-[0.24em] text-cyan-700/75">Modelo</p>
                                <p class="mt-2 text-xl font-semibold text-slate-950">{{ $product->model }}</p>
                            </div>
                        @endif

                        @if (filled($product->sku))
                            <div class="site-outline rounded-[26px] p-5">
                                <p class="text-xs font-semibold uppercase tracking-[0.24em] text-cyan-700/75">SKU</p>
                                <p class="mt-2 text-xl font-semibold text-slate-950">{{ $product->sku }}</p>
                            </div>
                        @endif
                    </div>

                    <div class="mt-8 flex flex-wrap gap-3">
                        <a href="#ficha-tecnica" class="site-button-primary">
                            Ver ficha tecnica
                        </a>

                        @if ($product->attachments->isNotEmpty())
                            <a href="#documentos" class="site-button-secondary">
                                Ver documentos
                            </a>
                        @endif
                    </div>
                </article>
            </div>
        </div>
    </section>

    <section id="ficha-tecnica" class="px-4 py-6 sm:px-6 lg:px-8">
        <div class="site-shell">
            <div class="grid gap-6 xl:grid-cols-[minmax(300px,0.82fr)_minmax(0,1.18fr)]">
                <article class="site-panel rounded-[38px] p-6 sm:p-8">
                    <p class="site-section-kicker">Datos del producto</p>
                    <h2 class="font-display mt-4 text-2xl font-semibold tracking-tight text-slate-950 sm:text-3xl">
                        Informacion registrada
                    </h2>

                    <div class="mt-6 grid gap-3">
                        @foreach ($detailRows as $row)
                            <div class="flex flex-wrap items-center justify-between gap-3 rounded-[22px] border border-slate-200/80 bg-white/82 px-4 py-3">
                                <span class="text-sm text-slate-500">{{ $row['label'] }}</span>
                                <span class="text-right text-sm font-semibold text-slate-950">{{ $row['value'] }}</span>
                            </div>
                        @endforeach
                    </div>
                </article>

                <article class="site-panel rounded-[38px] p-6 sm:p-8">
                    <p class="site-section-kicker">Descripcion</p>
                    <h2 class="font-display mt-4 text-2xl font-semibold tracking-tight text-slate-950 sm:text-3xl">
                        Detalle comercial y tecnico
                    </h2>

                    <div class="mt-6 rounded-[28px] border border-slate-200/80 bg-white/82 p-5 text-sm leading-7 text-slate-600 sm:text-base sm:leading-8">
                        @if ($fullDescription !== '')
                            {!! nl2br(e($fullDescription)) !!}
                        @else
                            Este producto aun no tiene una descripcion larga registrada. La ficha conserva sus datos principales, parametros, especificaciones y documentos disponibles.
                        @endif
                    </div>
                </article>
            </div>
        </div>
    </section>

    <section class="px-4 py-6 sm:px-6 lg:px-8">
        <div class="site-shell">
            <div class="grid gap-6 xl:grid-cols-2">
                <article class="site-panel rounded-[38px] p-6 sm:p-8">
                    <p class="site-section-kicker">Especificaciones</p>
                    <h2 class="font-display mt-4 text-2xl font-semibold tracking-tight text-slate-950 sm:text-3xl">
                        Especificaciones tecnicas
                    </h2>

                    <div class="mt-6 grid gap-3">
                        @forelse ($product->specifications as $specification)
                            <div class="rounded-[24px] border border-slate-200/80 bg-white/82 p-4">
                                <p class="text-sm font-semibold text-slate-950">{{ $specification->label }}</p>
                                <p class="mt-2 text-sm leading-6 text-slate-600">
                                    {{ $specification->value }}@if (filled($specification->unit)) <span class="font-medium text-slate-900">{{ $specification->unit }}</span>@endif
                                </p>
                            </div>
                        @empty
                            <div class="rounded-[24px] border border-dashed border-slate-300 bg-slate-50/90 p-5 text-sm leading-7 text-slate-600">
                                No hay especificaciones tecnicas adicionales registradas para este producto.
                            </div>
                        @endforelse
                    </div>
                </article>

                <article class="site-panel rounded-[38px] p-6 sm:p-8">
                    <p class="site-section-kicker">Parametros</p>
                    <h2 class="font-display mt-4 text-2xl font-semibold tracking-tight text-slate-950 sm:text-3xl">
                        Parametros del catalogo
                    </h2>

                    <div class="mt-6 grid gap-3">
                        @forelse ($product->parameterValues as $parameterValue)
                            @php
                                $parameterText = filled($parameterValue->value_text)
                                    ? $parameterValue->value_text
                                    : rtrim(rtrim((string) $parameterValue->value_number, '0'), '.');
                                $unitText = $parameterValue->unit?->symbol ?: $parameterValue->unit?->name;
                            @endphp

                            <div class="flex flex-wrap items-center justify-between gap-3 rounded-[24px] border border-slate-200/80 bg-white/82 p-4">
                                <p class="text-sm font-semibold text-slate-950">{{ $parameterValue->parameter?->name ?? 'Parametro' }}</p>
                                <p class="text-sm leading-6 text-slate-600">
                                    {{ $parameterText }}@if (filled($unitText)) <span class="font-medium text-slate-900">{{ $unitText }}</span>@endif
                                </p>
                            </div>
                        @empty
                            <div class="rounded-[24px] border border-dashed border-slate-300 bg-slate-50/90 p-5 text-sm leading-7 text-slate-600">
                                No hay parametros adicionales registrados para este producto.
                            </div>
                        @endforelse
                    </div>
                </article>
            </div>
        </div>
    </section>

    @if ($product->attachments->isNotEmpty())
        <section id="documentos" class="px-4 py-6 sm:px-6 lg:px-8">
            <div class="site-shell">
                <article class="site-panel rounded-[38px] p-6 sm:p-8">
                    <div class="flex flex-wrap items-end justify-between gap-5">
                        <div>
                            <p class="site-section-kicker">Documentos</p>
                            <h2 class="font-display mt-4 text-2xl font-semibold tracking-tight text-slate-950 sm:text-3xl">
                                Archivos disponibles
                            </h2>
                        </div>

                        <span class="rounded-full border border-cyan-200/70 bg-cyan-50/90 px-4 py-2 text-sm font-semibold text-cyan-900">
                            {{ $product->attachments->count() }} {{ \Illuminate\Support\Str::plural('archivo', $product->attachments->count()) }}
                        </span>
                    </div>

                    <div class="mt-6 grid gap-3 md:grid-cols-2">
                        @foreach ($product->attachments as $attachment)
                            <a
                                href="{{ route('site.product-attachments.download', $attachment) }}"
                                class="group flex items-center justify-between gap-4 rounded-[26px] border border-slate-200/80 bg-white/82 p-5 text-slate-700 transition hover:border-cyan-200 hover:bg-cyan-50/80"
                            >
                                <span class="min-w-0">
                                    <span class="block truncate text-sm font-semibold text-slate-950">{{ $attachment->original_name ?: basename($attachment->path) }}</span>
                                    <span class="mt-1 block text-xs text-slate-500">{{ $formatFileSize($attachment->size) }}</span>
                                </span>

                                <span class="inline-flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl bg-slate-950 text-white transition group-hover:bg-cyan-700">
                                    <svg class="h-4 w-4" viewBox="0 0 20 20" fill="none" aria-hidden="true">
                                        <path d="M10 3.5V12M10 12L6.75 8.75M10 12L13.25 8.75M5 15.5H15" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </span>
                            </a>
                        @endforeach
                    </div>
                </article>
            </div>
        </section>
    @endif

    @if ($relatedProducts->isNotEmpty())
        <section class="px-4 py-6 sm:px-6 lg:px-8">
            <div class="site-shell">
                <article class="site-panel rounded-[38px] p-6 sm:p-8">
                    <div class="flex flex-wrap items-end justify-between gap-5">
                        <div>
                            <p class="site-section-kicker">Tambien puede servirte</p>
                            <h2 class="font-display mt-4 text-2xl font-semibold tracking-tight text-slate-950 sm:text-3xl">
                                Productos relacionados
                            </h2>
                        </div>

                        <a href="{{ route('home') }}#portafolio" class="site-button-secondary">
                            Ver catalogo
                        </a>
                    </div>

                    <div class="mt-6 grid gap-4 md:grid-cols-2 xl:grid-cols-4">
                        @foreach ($relatedProducts as $relatedProduct)
                            @php
                                $relatedBrand = $relatedProduct->productBrand?->name ?? $relatedProduct->brand ?? 'Sin marca';
                                $relatedCategory = $relatedProduct->category?->name ?? 'Sin categoria';
                            @endphp

                            <a href="{{ route('site.products.show', ['product' => $relatedProduct->code]) }}" class="site-outline group overflow-hidden rounded-[30px] bg-white/86 transition hover:border-cyan-200">
                                <span class="block h-40 bg-[linear-gradient(180deg,_rgba(240,249,255,0.96)_0%,_rgba(255,255,255,0.92)_100%)] p-4">
                                    <img
                                        src="{{ $relatedProduct->hasFeaturedImage() ? route('site.products.featured-image', $relatedProduct) : asset('branding/site/hero-monitor.svg') }}"
                                        alt="{{ $relatedProduct->name }}"
                                        class="h-full w-full object-contain object-center transition group-hover:scale-[1.03]"
                                    >
                                </span>

                                <span class="block p-5">
                                    <span class="block text-xs font-semibold uppercase tracking-[0.2em] text-cyan-700/75">{{ $relatedCategory }}</span>
                                    <span class="mt-3 block text-base font-semibold leading-6 text-slate-950">{{ $relatedProduct->name }}</span>
                                    <span class="mt-2 block text-sm text-slate-600">{{ $relatedBrand }}</span>
                                </span>
                            </a>
                        @endforeach
                    </div>
                </article>
            </div>
        </section>
    @endif
@endsection
