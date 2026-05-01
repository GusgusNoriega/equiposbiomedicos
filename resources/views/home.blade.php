@extends('layouts.site')

@section('title', 'Equipos Biomedicos y Servicios')
@section('company-name', $companyName)
@section('meta-description', 'Venta de equipos biomedicos, mantenimiento preventivo y mantenimiento correctivo para instituciones de salud y servicios clinicos.')

@section('content')
    <section id="inicio" class="pb-6 pt-2">
        <section class="site-hero-slider enter-fade relative overflow-hidden" data-site-slider>
            <div class="site-pattern absolute inset-0 opacity-35"></div>
            <div class="absolute -left-24 top-14 h-80 w-80 rounded-full bg-cyan-200/18 blur-3xl"></div>
            <div class="absolute right-0 top-0 h-96 w-96 rounded-full bg-white/10 blur-3xl"></div>
            <div class="absolute bottom-0 right-1/4 h-72 w-72 rounded-full bg-emerald-300/16 blur-3xl"></div>

            <div class="relative min-h-[700px] sm:min-h-[740px] xl:min-h-[860px]">
                @foreach ($heroSlides as $slide)
                    <article class="site-slide h-full" data-site-slide data-active="{{ $loop->first ? 'true' : 'false' }}">
                        <div class="site-shell flex h-full flex-col justify-center px-4 py-8 sm:px-6 sm:py-10 lg:px-8 lg:py-12">
                            <div class="grid h-full gap-5 xl:grid-cols-[minmax(0,0.9fr)_minmax(0,1.1fr)] xl:items-center">
                                <div class="site-hero-copy flex flex-col rounded-[36px] p-6 text-white sm:p-8 xl:p-8">
                                    <div>
                                        <span class="inline-flex items-center gap-2 rounded-full border border-white/12 bg-white/10 px-4 py-2 text-[10px] font-semibold uppercase tracking-[0.25em] text-cyan-50/90 backdrop-blur">
                                            Venta, soporte y mantenimiento
                                        </span>

                                        <p class="mt-5 text-[11px] font-semibold uppercase tracking-[0.28em] text-cyan-100/72">{{ $slide['eyebrow'] }}</p>

                                        @if ($loop->first)
                                            <h1 class="site-hero-title font-display mt-3 font-semibold text-white">
                                                {{ $slide['title'] }}
                                            </h1>
                                        @else
                                            <h2 class="site-hero-title font-display mt-3 font-semibold text-white">
                                                {{ $slide['title'] }}
                                            </h2>
                                        @endif

                                        <p class="site-hero-description mt-4">
                                            {{ $slide['description'] }}
                                        </p>
                                    </div>

                                    <div class="mt-6 grid gap-2.5">
                                        @foreach ($slide['highlights'] as $highlight)
                                            <div class="site-dark-outline flex items-start gap-3 rounded-[22px] px-4 py-3">
                                                <span class="mt-1 h-2.5 w-2.5 rounded-full bg-emerald-400"></span>
                                                <p class="site-hero-highlight-text text-slate-100/88">{{ $highlight }}</p>
                                            </div>
                                        @endforeach
                                    </div>

                                    <div class="mt-6 flex flex-wrap gap-3">
                                        <a href="#portafolio" class="site-button-secondary border-white/10 bg-white text-slate-950 shadow-[0_18px_36px_rgba(255,255,255,0.12)] hover:border-cyan-200 hover:bg-cyan-50 hover:text-slate-950">
                                            Explorar portafolio
                                        </a>

                                        @auth
                                            <a href="{{ route('admin.dashboard') }}" class="site-button-secondary border-white/12 bg-white/10 text-white shadow-none hover:border-white/22 hover:bg-white/16 hover:text-white">
                                                Ir al panel administrativo
                                            </a>
                                        @else
                                            <a href="{{ route('login') }}" class="site-button-secondary border-white/12 bg-white/10 text-white shadow-none hover:border-white/22 hover:bg-white/16 hover:text-white">
                                                Ingresar al portal
                                            </a>
                                        @endauth
                                    </div>
                                </div>

                                <div class="site-hero-media relative overflow-hidden rounded-[38px]">
                                    <div class="absolute inset-x-0 top-0 h-44 bg-[radial-gradient(circle_at_center,_rgba(255,255,255,0.88),_transparent_70%)]"></div>
                                    <div class="absolute inset-y-10 left-10 w-24 rounded-full bg-cyan-100/14 blur-3xl"></div>
                                    <div class="absolute bottom-6 right-8 h-28 w-28 rounded-full bg-emerald-300/20 blur-3xl"></div>

                                    <div class="relative flex min-h-[340px] items-center justify-center sm:min-h-[430px] xl:min-h-[560px]">
                                        <img
                                            src="{{ $slide['image'] }}"
                                            alt="{{ $slide['title'] }}"
                                            class="absolute inset-0 h-full w-full object-contain object-center p-8 sm:p-10 xl:p-14"
                                        >
                                    </div>

                                    <div class="absolute left-5 top-5 grid max-w-[15rem] grid-cols-2 gap-2 sm:left-6 sm:top-6 sm:max-w-[17rem]">
                                        @foreach ($metrics as $metric)
                                            <div class="min-w-0 rounded-[22px] border border-white/12 bg-slate-950/48 px-3 py-3 text-white shadow-lg shadow-slate-950/18 backdrop-blur">
                                                <p class="site-hero-stat-value font-display font-semibold tracking-tight">{{ $metric['value'] }}</p>
                                                <p class="site-hero-stat-label mt-1 uppercase text-cyan-100/72">{{ $metric['label'] }}</p>
                                            </div>
                                        @endforeach
                                    </div>

                                    <div class="absolute inset-x-4 bottom-4 rounded-[28px] border border-white/10 bg-slate-950/76 px-5 py-3.5 text-white shadow-xl shadow-slate-950/30 backdrop-blur-sm sm:inset-x-6 sm:bottom-6 sm:px-6">
                                        <p class="text-xs font-semibold uppercase tracking-[0.26em] text-cyan-100/72">Enfoque</p>
                                        <p class="site-hero-caption-title mt-2 font-semibold">{{ $slide['caption_title'] }}</p>
                                        <p class="site-hero-caption-copy mt-2 text-slate-300">{{ $slide['caption'] }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>

            <div class="site-shell relative px-4 pb-6 sm:px-6 lg:px-8">
                <div class="site-hero-toolbar flex flex-wrap items-center justify-between gap-4 rounded-[30px] px-5 py-4 sm:px-6">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.3em] text-cyan-700/75">Slider principal</p>
                        <p class="site-hero-toolbar-copy mt-2 text-slate-600">La portada abre con el componente visual principal y deja el contenido institucional justo despues.</p>
                    </div>

                    <div class="flex flex-wrap items-center gap-4 sm:gap-6">
                        <div class="flex items-center gap-2">
                            @foreach ($heroSlides as $slide)
                                <button
                                    type="button"
                                    class="site-slider-indicator"
                                    data-site-indicator="{{ $loop->index }}"
                                    data-active="{{ $loop->first ? 'true' : 'false' }}"
                                    aria-label="Ir a la diapositiva {{ $loop->iteration }}"
                                ></button>
                            @endforeach
                        </div>

                        <div class="flex items-center gap-2">
                            <button type="button" class="site-slider-control" data-site-prev aria-label="Anterior">
                                <svg class="h-4 w-4" viewBox="0 0 20 20" fill="none" aria-hidden="true">
                                    <path d="M11.75 5.75L7.5 10L11.75 14.25" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </button>

                            <button type="button" class="site-slider-control" data-site-next aria-label="Siguiente">
                                <svg class="h-4 w-4" viewBox="0 0 20 20" fill="none" aria-hidden="true">
                                    <path d="M8.25 5.75L12.5 10L8.25 14.25" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </section>

    <section class="px-4 pb-6 sm:px-6 lg:px-8">
        <div class="site-shell">
            <div class="grid gap-6 xl:grid-cols-[minmax(0,1.02fr)_minmax(320px,0.98fr)]">
                <article class="site-panel enter-fade relative overflow-hidden rounded-[40px] p-8 sm:p-10" style="animation-delay: 120ms;">
                    <div class="absolute -right-10 top-12 h-44 w-44 rounded-full bg-cyan-200/45 blur-3xl"></div>
                    <div class="absolute bottom-0 left-0 h-56 w-56 rounded-full bg-emerald-200/35 blur-3xl"></div>

                    <div class="relative">
                        <span class="site-chip inline-flex items-center gap-2 rounded-full px-4 py-2 text-xs font-semibold uppercase tracking-[0.28em] text-cyan-700">
                            Presencia institucional
                        </span>

                        <p class="site-section-kicker mt-8">Equipos biomedicos y servicios</p>

                        <h2 class="font-display mt-4 max-w-3xl text-3xl font-semibold tracking-tight text-slate-950 sm:text-4xl xl:text-5xl">
                            Soluciones biomedicas con una portada mas clara, tecnica y comercial.
                        </h2>

                        <p class="mt-5 max-w-3xl text-base leading-8 text-slate-600 sm:text-lg">
                            El slider ahora abre la experiencia y el discurso institucional queda organizado debajo en bloques mas respirados. Asi la pagina de inicio presenta primero el impacto visual y luego desarrolla servicios, portafolio y soporte.
                        </p>

                        <div class="mt-10 grid gap-4 md:grid-cols-2">
                            <article class="site-outline rounded-[28px] p-5">
                                <p class="text-xs font-semibold uppercase tracking-[0.28em] text-cyan-700/75">Lectura comercial</p>
                                <p class="mt-3 text-lg font-semibold text-slate-950">La primera pantalla deja claro que vendes, mantienes y das soporte.</p>
                            </article>

                            <article class="site-outline rounded-[28px] p-5">
                                <p class="text-xs font-semibold uppercase tracking-[0.28em] text-cyan-700/75">Base reusable</p>
                                <p class="mt-3 text-lg font-semibold text-slate-950">La composicion quedo lista para seguir creciendo hacia otras vistas publicas.</p>
                            </article>
                        </div>
                    </div>
                </article>

                <div class="grid gap-6">
                    <div class="grid gap-4 sm:grid-cols-2">
                        @foreach ($metrics as $metric)
                            <article class="site-stat-card rounded-[28px] p-5">
                                <p class="font-display text-2xl font-semibold tracking-tight text-slate-950 sm:text-3xl">{{ $metric['value'] }}</p>
                                <p class="mt-2 text-sm leading-6 text-slate-600">{{ $metric['label'] }}</p>
                            </article>
                        @endforeach
                    </div>

                    <article class="site-panel rounded-[40px] p-6 sm:p-7">
                        <p class="site-section-kicker">Base del sitio</p>

                        <div class="mt-6 grid gap-3">
                            @foreach ($qualityPillars as $pillar)
                                <article class="site-outline flex items-start gap-4 rounded-[26px] p-4">
                                    <span class="mt-1 inline-flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl bg-slate-950 text-sm font-semibold text-white">
                                        {{ $loop->iteration }}
                                    </span>

                                    <div>
                                        <h2 class="text-base font-semibold text-slate-950">{{ $pillar['title'] }}</h2>
                                        <p class="mt-2 text-sm leading-6 text-slate-600">{{ $pillar['description'] }}</p>
                                    </div>
                                </article>
                            @endforeach
                        </div>
                    </article>
                </div>
            </div>
        </div>
    </section>

    <section id="servicios" class="px-4 py-6 sm:px-6 lg:px-8">
        <div class="site-shell">
            <div class="site-panel rounded-[40px] p-8 sm:p-10">
                <div class="flex flex-wrap items-end justify-between gap-6">
                    <div class="max-w-3xl">
                        <p class="site-section-kicker">Servicios principales</p>
                        <h2 class="font-display mt-4 text-3xl font-semibold tracking-tight text-slate-950 sm:text-4xl">
                            Secciones construidas a partir de lo que ofreces hoy.
                        </h2>
                        <p class="mt-4 text-base leading-8 text-slate-600">
                            La portada reparte el discurso comercial entre venta de equipos, mantenimiento preventivo, mantenimiento correctivo y el fortalecimiento del catalogo de productos.
                        </p>
                    </div>

                    <div class="rounded-[26px] border border-cyan-200/70 bg-cyan-50/90 px-5 py-4 text-sm leading-6 text-cyan-950">
                        Estructura reutilizable para futuras paginas publicas.
                    </div>
                </div>

                <div class="mt-8 grid gap-4 lg:grid-cols-4">
                    @foreach ($services as $service)
                        <article class="service-card relative overflow-hidden rounded-[30px] border border-slate-200/80 bg-white/85 p-6 shadow-sm shadow-cyan-900/8">
                            <div class="absolute inset-x-0 top-0 h-28" style="background: {{ $service['accent'] }};"></div>
                            <div class="relative">
                                <span class="inline-flex h-12 w-12 items-center justify-center rounded-2xl bg-slate-950 text-sm font-semibold text-white">
                                    {{ $loop->iteration }}
                                </span>
                                <h3 class="mt-6 text-xl font-semibold text-slate-950">{{ $service['title'] }}</h3>
                                <p class="mt-4 text-sm leading-7 text-slate-600">{{ $service['description'] }}</p>
                            </div>
                        </article>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    <section id="portafolio" class="px-4 py-6 sm:px-6 lg:px-8">
        <div class="site-shell">
            @php
                $hasCatalogFilters = ! is_null($catalogFilters['brand']) || ! is_null($catalogFilters['category']);
            @endphp

            <div class="site-panel rounded-[40px] p-8 sm:p-10">
                <div class="flex flex-wrap items-end justify-between gap-6">
                    <div class="max-w-3xl">
                        <p class="site-section-kicker">Portafolio</p>
                        <h2 class="font-display mt-4 text-3xl font-semibold tracking-tight text-slate-950 sm:text-4xl">
                            Productos actuales registrados en el sistema.
                        </h2>
                        <p class="mt-4 text-base leading-8 text-slate-600">
                            Esta seccion ahora muestra solo productos activos del catalogo real. Puedes filtrarlos por marca y categoria, y cuando haya mas de ocho registros se paginan automaticamente.
                        </p>
                    </div>

                    <div class="rounded-[26px] border border-cyan-200/70 bg-cyan-50/90 px-5 py-4 text-sm leading-6 text-cyan-950">
                        {{ $catalogProducts->total() }} {{ \Illuminate\Support\Str::plural('producto', $catalogProducts->total()) }} encontrados
                    </div>
                </div>

                <form method="GET" action="{{ route('home') }}#portafolio" class="mt-8 grid gap-4 rounded-[32px] border border-slate-200/80 bg-white/80 p-5 shadow-sm shadow-cyan-900/8 lg:grid-cols-[minmax(0,1fr)_minmax(0,1fr)_auto_auto] lg:items-end">
                    <label class="grid gap-2">
                        <span class="text-xs font-semibold uppercase tracking-[0.24em] text-cyan-700/75">Marca</span>
                        <select
                            name="marca"
                            class="rounded-[22px] border border-slate-200/80 bg-white px-4 py-3 text-sm text-slate-700 outline-none transition focus:border-cyan-300 focus:ring-2 focus:ring-cyan-100"
                        >
                            <option value="">Todas las marcas</option>
                            @foreach ($catalogBrands as $brand)
                                <option value="{{ $brand['id'] }}" @selected($catalogFilters['brand'] === $brand['id'])>
                                    {{ $brand['name'] }}
                                </option>
                            @endforeach
                        </select>
                    </label>

                    <label class="grid gap-2">
                        <span class="text-xs font-semibold uppercase tracking-[0.24em] text-cyan-700/75">Categoria</span>
                        <select
                            name="categoria"
                            class="rounded-[22px] border border-slate-200/80 bg-white px-4 py-3 text-sm text-slate-700 outline-none transition focus:border-cyan-300 focus:ring-2 focus:ring-cyan-100"
                        >
                            <option value="">Todas las categorias</option>
                            @foreach ($catalogCategories as $category)
                                <option value="{{ $category['id'] }}" @selected($catalogFilters['category'] === $category['id'])>
                                    {{ $category['name'] }}
                                </option>
                            @endforeach
                        </select>
                    </label>

                    <button type="submit" class="site-button-primary justify-center">
                        Filtrar productos
                    </button>

                    <a href="{{ route('home') }}#portafolio" class="site-button-secondary justify-center">
                        Limpiar filtros
                    </a>
                </form>

                @if ($catalogProducts->count() > 0)
                    <div class="mt-8 grid gap-4 md:grid-cols-2 xl:grid-cols-4">
                        @foreach ($catalogProducts as $product)
                            @php
                                $simpleDescription = trim((string) ($product->short_description ?: strip_tags((string) $product->description)));
                                $productBrand = $product->productBrand?->name ?? $product->brand ?? 'Sin marca';
                                $productCategory = $product->category?->name ?? 'Sin categoria';
                            @endphp

                            <article class="site-panel overflow-hidden rounded-[32px] border border-slate-200/80 bg-white/92">
                                <div class="relative h-52 overflow-hidden bg-[linear-gradient(180deg,_rgba(240,249,255,0.92)_0%,_rgba(224,242,254,0.55)_32%,_rgba(255,255,255,0.95)_100%)]">
                                    <span class="absolute left-4 top-4 z-10 rounded-full bg-slate-950 px-3 py-1 text-[11px] font-semibold uppercase tracking-[0.22em] text-white">
                                        {{ $productCategory }}
                                    </span>

                                    <img
                                        src="{{ $product->hasFeaturedImage() ? route('site.products.featured-image', $product) : asset('branding/site/hero-monitor.svg') }}"
                                        alt="{{ $product->name }}"
                                        class="h-full w-full object-contain object-center p-5"
                                    >
                                </div>

                                <div class="p-5">
                                    <h3 class="text-lg font-semibold leading-7 text-slate-950">{{ $product->name }}</h3>

                                    @if (filled($simpleDescription))
                                        <p class="mt-3 text-sm leading-7 text-slate-600">
                                            {{ \Illuminate\Support\Str::limit($simpleDescription, 110) }}
                                        </p>
                                    @endif

                                    <div class="mt-5 grid gap-2 border-t border-slate-200/80 pt-4 text-sm text-slate-600">
                                        <div class="flex items-center justify-between gap-3">
                                            <span>Marca</span>
                                            <span class="text-right font-medium text-slate-900">{{ $productBrand }}</span>
                                        </div>

                                        <div class="flex items-center justify-between gap-3">
                                            <span>Categoria</span>
                                            <span class="text-right font-medium text-slate-900">{{ $productCategory }}</span>
                                        </div>

                                        @if (filled($product->model))
                                            <div class="flex items-center justify-between gap-3">
                                                <span>Modelo</span>
                                                <span class="text-right font-medium text-slate-900">{{ $product->model }}</span>
                                            </div>
                                        @endif

                                        <div class="flex items-center justify-between gap-3">
                                            <span>Stock</span>
                                            <span class="text-right font-medium text-slate-900">
                                                {{ is_null($product->stock_actual) ? 'No registrado' : $product->stock_actual }}
                                            </span>
                                        </div>
                                    </div>

                                    <a href="{{ route('site.products.show', ['product' => $product->code]) }}" class="site-button-primary mt-5 w-full justify-center">
                                        Ver ficha
                                    </a>
                                </div>
                            </article>
                        @endforeach
                    </div>

                    @if ($catalogProducts->hasPages())
                        <div class="mt-8">
                            {{ $catalogProducts->onEachSide(1)->links() }}
                        </div>
                    @endif
                @else
                    <div class="mt-8 rounded-[30px] border border-dashed border-slate-300 bg-slate-50/90 p-8 text-center">
                        <p class="text-lg font-semibold text-slate-950">
                            @if ($hasCatalogFilters)
                                No hay productos activos que coincidan con esos filtros.
                            @else
                                No hay productos activos registrados para mostrar en la pagina de inicio.
                            @endif
                        </p>
                        <p class="mt-3 text-sm leading-7 text-slate-600">
                            @if ($hasCatalogFilters)
                                Ajusta la marca o la categoria para ampliar los resultados.
                            @else
                                Cuando registres productos activos en el catalogo, apareceran automaticamente aqui.
                            @endif
                        </p>
                    </div>
                @endif
            </div>
        </div>
    </section>

    <section id="mantenimiento" class="px-4 py-6 sm:px-6 lg:px-8">
        <div class="site-shell">
            <div class="grid gap-6 xl:grid-cols-[minmax(0,1.08fr)_minmax(320px,0.92fr)]">
                <div class="grid gap-4 md:grid-cols-2">
                    @foreach ($maintenancePlans as $plan)
                        <article class="site-panel rounded-[34px] p-6 sm:p-7">
                            <p class="site-section-kicker text-[11px]">{{ $plan['title'] }}</p>
                            <h2 class="font-display mt-4 text-2xl font-semibold tracking-tight text-slate-950">
                                {{ $plan['title'] }}
                            </h2>
                            <p class="mt-4 text-sm leading-7 text-slate-600">{{ $plan['description'] }}</p>

                            <div class="mt-6 grid gap-3">
                                @foreach ($plan['steps'] as $step)
                                    <div class="flex items-start gap-3 rounded-[22px] border border-slate-200/80 bg-white/80 px-4 py-3">
                                        <span class="mt-1 inline-flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-emerald-100 text-xs font-semibold text-emerald-700">
                                            {{ $loop->iteration }}
                                        </span>
                                        <p class="text-sm leading-6 text-slate-700">{{ $step }}</p>
                                    </div>
                                @endforeach
                            </div>
                        </article>
                    @endforeach
                </div>

                <aside class="site-panel relative overflow-hidden rounded-[40px] p-8 sm:p-10">
                    <div class="absolute -right-10 top-10 h-44 w-44 rounded-full bg-cyan-200/35 blur-3xl"></div>
                    <div class="absolute bottom-0 left-0 h-44 w-44 rounded-full bg-emerald-200/30 blur-3xl"></div>

                    <div class="relative">
                        <p class="site-section-kicker">Continuidad operativa</p>
                        <h2 class="font-display mt-4 text-3xl font-semibold tracking-tight text-slate-950 sm:text-4xl">
                            Una narrativa clara para soporte preventivo y correctivo.
                        </h2>
                        <p class="mt-4 text-base leading-8 text-slate-600">
                            La nueva pagina de inicio no solo vende equipos: tambien deja visible que la empresa acompana el ciclo operativo del activo biomedico con mantenimiento y soporte tecnico.
                        </p>

                        <div class="mt-8 rounded-[30px] border border-slate-200/80 bg-white/88 p-5 shadow-sm shadow-cyan-900/8">
                            <p class="text-sm font-semibold uppercase tracking-[0.28em] text-cyan-700/75">Resultado</p>
                            <p class="mt-3 text-lg font-semibold text-slate-950">
                                Una portada mas institucional, con identidad visual propia y preparada para crecer hacia un sitio publico completo.
                            </p>
                        </div>

                        <div class="mt-8 flex flex-wrap gap-3">
                            <a href="#inicio" class="site-button-secondary">
                                Volver al inicio
                            </a>

                            @auth
                                <a href="{{ route('admin.dashboard') }}" class="site-button-primary">
                                    Continuar al panel
                                </a>
                            @else
                                <a href="{{ route('login') }}" class="site-button-primary">
                                    Ingresar al portal
                                </a>
                            @endauth
                        </div>
                    </div>
                </aside>
            </div>
        </div>
    </section>
@endsection
