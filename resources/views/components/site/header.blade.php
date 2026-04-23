@props([
    'navigation' => [],
    'companyName' => 'Equipos Biomedicos y Servicios',
])

<header class="sticky top-0 z-50 px-4 pt-4 sm:px-6 lg:px-8">
    <div class="site-shell">
        <div class="site-panel rounded-[34px] px-4 py-4 sm:px-6">
            <div class="flex flex-wrap items-center gap-4">
                <a href="{{ route('home') }}" class="flex min-w-0 flex-1 items-center gap-4">
                    <span class="rounded-[24px] border border-white/70 bg-white/95 px-3 py-3 shadow-sm shadow-cyan-900/10">
                        <x-brand.logo class="w-[150px] sm:w-[190px]" />
                    </span>

                    <span class="hidden min-w-0 xl:block">
                        <span class="site-section-kicker text-[10px]">Equipos biomedicos</span>
                        <span class="mt-2 block truncate text-base font-semibold text-slate-950">{{ $companyName }}</span>
                        <span class="mt-1 block text-sm text-slate-600">Venta, mantenimiento preventivo y correctivo.</span>
                    </span>
                </a>

                <button
                    type="button"
                    class="inline-flex h-12 w-12 items-center justify-center rounded-2xl border border-slate-200 bg-white/90 text-slate-700 shadow-sm transition hover:border-cyan-200 hover:bg-cyan-50 lg:hidden"
                    data-site-nav-toggle
                    aria-expanded="false"
                    aria-controls="site-navigation"
                    aria-label="Abrir menu"
                >
                    <svg class="h-5 w-5" viewBox="0 0 20 20" fill="none" aria-hidden="true">
                        <path d="M4 6H16M4 10H16M4 14H11.5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" />
                    </svg>
                </button>

                <div class="hidden items-center gap-3 lg:flex">
                    <nav class="flex items-center gap-1 rounded-full border border-slate-200/80 bg-white/80 p-1 shadow-sm">
                        @foreach ($navigation as $item)
                            <a
                                href="{{ $item['href'] }}"
                                class="rounded-full px-4 py-2 text-sm font-medium text-slate-600 transition hover:bg-slate-950 hover:text-white"
                            >
                                {{ $item['label'] }}
                            </a>
                        @endforeach
                    </nav>

                    <a href="#portafolio" class="site-button-secondary">
                        Ver portafolio
                    </a>

                    @auth
                        <a href="{{ route('admin.dashboard') }}" class="site-button-primary">
                            Ir al panel
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="site-button-primary">
                            Ingresar al portal
                        </a>
                    @endauth
                </div>
            </div>

            <div id="site-navigation" class="hidden border-t border-slate-200/80 pt-4 lg:hidden" data-site-nav-panel>
                <nav class="grid gap-2">
                    @foreach ($navigation as $item)
                        <a
                            href="{{ $item['href'] }}"
                            class="rounded-[20px] border border-slate-200/80 bg-white/85 px-4 py-3 text-sm font-medium text-slate-700 transition hover:border-cyan-200 hover:bg-cyan-50"
                        >
                            {{ $item['label'] }}
                        </a>
                    @endforeach
                </nav>

                <div class="mt-4 grid gap-3 sm:grid-cols-2">
                    <a href="#portafolio" class="site-button-secondary justify-center">
                        Ver portafolio
                    </a>

                    @auth
                        <a href="{{ route('admin.dashboard') }}" class="site-button-primary justify-center">
                            Ir al panel
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="site-button-primary justify-center">
                            Ingresar al portal
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </div>
</header>
