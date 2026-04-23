@php
    $currentUser = auth()->user()?->loadMissing('role.permissions');
    $navigation = collect(config('admin.navigation', []))
        ->map(function (array $section) use ($currentUser): array {
            $items = collect($section['items'] ?? [])
                ->filter(function (array $item) use ($currentUser): bool {
                    $requiredPermission = $item['permission'] ?? null;
                    $hasRoute = ! empty($item['route']) && Route::has($item['route']);

                    return $hasRoute && (
                        blank($requiredPermission)
                        || ($currentUser && $currentUser->hasPermission($requiredPermission))
                    );
                })
                ->values()
                ->all();

            return [
                ...$section,
                'items' => $items,
            ];
        })
        ->filter(fn (array $section): bool => ! empty($section['items']))
        ->values();
    $pageTitle = trim($__env->yieldContent('page-title', 'Centro de control'));
    $pageDescription = trim($__env->yieldContent('page-description', 'Gestiona inventario, mantenimiento y trazabilidad de equipos biomedicos.'));
    $pageKicker = trim($__env->yieldContent('page-kicker', 'Administrador biomedico'));
    $hasViteAssets = file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot'));

    $activeSectionIndex = $navigation->search(function (array $section): bool {
        return collect($section['items'] ?? [])->contains(function (array $item): bool {
            return ! empty($item['route'])
                && Route::has($item['route'])
                && request()->routeIs($item['route']);
        });
    });

    $defaultOpenIndex = $activeSectionIndex === false ? 0 : $activeSectionIndex;
@endphp

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ trim($__env->yieldContent('title', $pageTitle)) }} | {{ config('app.name', 'Laravel') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700,800|sora:500,600,700" rel="stylesheet" />

        @if ($hasViteAssets)
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @else
            @include('layouts.partials.admin-browser-tailwind')
        @endif
    </head>
    <body class="antialiased text-slate-900">
        <div aria-hidden="true" class="pointer-events-none fixed inset-0 overflow-hidden">
            <div class="grid-mesh absolute inset-0 opacity-60"></div>
            <div class="float-orb absolute -right-16 -top-16 h-72 w-72 rounded-full bg-cyan-300/35 blur-3xl"></div>
            <div class="float-orb absolute -bottom-20 left-0 h-80 w-80 rounded-full bg-emerald-300/30 blur-3xl [animation-delay:1.2s]"></div>
        </div>

        <div class="relative min-h-screen lg:grid lg:grid-cols-[320px_minmax(0,1fr)]">
            <div class="fixed inset-0 z-40 hidden bg-slate-950/30 backdrop-blur-sm lg:hidden" data-admin-overlay></div>

            <aside class="fixed inset-y-0 left-0 z-50 flex w-[86vw] max-w-[320px] -translate-x-full flex-col border-r border-white/10 bg-[linear-gradient(180deg,_#0f172a_0%,_#082f49_100%)] px-4 py-4 text-slate-100 shadow-2xl shadow-slate-900/30 transition-transform duration-300 ease-out lg:static lg:translate-x-0" data-admin-sidebar>
                <div class="relative rounded-[30px] border border-white/10 bg-white/5 p-5 backdrop-blur-sm">
                    <button type="button" class="absolute right-4 top-4 inline-flex h-10 w-10 items-center justify-center rounded-2xl border border-white/10 bg-white/5 text-slate-300 transition hover:bg-white/10 lg:hidden" data-admin-sidebar-close aria-label="Cerrar menu">
                        <svg class="h-4 w-4" viewBox="0 0 20 20" fill="none" aria-hidden="true">
                            <path d="M5 5L15 15M15 5L5 15" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" />
                        </svg>
                    </button>

                    <div class="rounded-[26px] border border-white/15 bg-white/95 px-4 py-4 shadow-xl shadow-slate-950/20">
                        <x-brand.logo class="mx-auto w-full max-w-[216px]" />
                    </div>

                    <div class="mt-4 flex items-start justify-between gap-3">
                        <div class="min-w-0">
                            <p class="text-xs font-semibold uppercase tracking-[0.34em] text-cyan-100/65">Panel administrativo</p>
                            <p class="mt-2 text-sm leading-6 text-slate-300/90">
                                Base administrativa para inventario, mantenimiento, calibracion y cumplimiento regulatorio.
                            </p>
                        </div>

                        <span class="rounded-full border border-white/10 bg-white/10 px-3 py-1 text-[10px] font-semibold uppercase tracking-[0.24em] text-cyan-100">
                            Acceso interno
                        </span>
                    </div>
                </div>

                <nav class="mt-6 flex-1 space-y-3 overflow-y-auto pr-1">
                    @foreach ($navigation as $index => $section)
                        @php
                            $sectionIsActive = collect($section['items'] ?? [])->contains(function (array $item): bool {
                                return ! empty($item['route'])
                                    && Route::has($item['route'])
                                    && request()->routeIs($item['route']);
                            });

                            $isOpen = $index === $defaultOpenIndex;
                        @endphp

                        <section class="rounded-[26px] border border-white/10 bg-white/5 p-2.5 backdrop-blur-sm" data-admin-accordion-section>
                            <button
                                type="button"
                                class="flex w-full items-center gap-3 rounded-[20px] px-3 py-3 text-left transition hover:bg-white/5"
                                data-admin-accordion-trigger
                                aria-expanded="{{ $isOpen ? 'true' : 'false' }}"
                            >
                                <span class="flex h-11 w-11 items-center justify-center rounded-2xl {{ $sectionIsActive ? 'bg-cyan-300/20 text-cyan-100' : 'bg-white/10 text-slate-300' }}">
                                    <x-admin.icon :name="$section['icon'] ?? 'overview'" class="h-5 w-5" />
                                </span>

                                <span class="min-w-0 flex-1">
                                    <span class="block truncate text-sm font-semibold text-white">{{ $section['label'] }}</span>
                                    <span class="mt-0.5 block text-xs leading-5 text-slate-400">{{ $section['description'] ?? '' }}</span>
                                </span>

                                <svg class="h-4 w-4 shrink-0 text-slate-400 transition {{ $isOpen ? 'rotate-180' : '' }}" viewBox="0 0 20 20" fill="none" aria-hidden="true" data-admin-chevron>
                                    <path d="M5 7.5L10 12.5L15 7.5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </button>

                            <div class="{{ $isOpen ? '' : 'hidden' }} px-2 pb-2" data-admin-accordion-panel>
                                <div class="soft-divider mb-3 opacity-70"></div>

                                <div class="space-y-2">
                                    @foreach ($section['items'] ?? [] as $item)
                                        @php
                                            $isActive = request()->routeIs($item['route']);
                                        @endphp

                                        <a
                                            href="{{ route($item['route']) }}"
                                            @class([
                                                'sidebar-link block rounded-2xl px-4 py-3 text-white',
                                                'sidebar-link-active' => $isActive,
                                            ])
                                        >
                                            <div class="flex items-start gap-3">
                                                <span class="mt-1.5 h-2.5 w-2.5 shrink-0 rounded-full {{ $isActive ? 'bg-emerald-300' : 'bg-cyan-200/70' }}"></span>
                                                <span class="min-w-0">
                                                    <span class="block text-sm font-medium">{{ $item['label'] }}</span>
                                                    <span class="mt-1 block text-xs leading-5 text-slate-400">{{ $item['description'] ?? '' }}</span>
                                                </span>
                                            </div>
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        </section>
                    @endforeach
                </nav>

                @if ($currentUser)
                    <div class="mt-6 rounded-[28px] border border-white/10 bg-white/5 p-4 text-sm text-slate-300 backdrop-blur-sm">
                        <p class="text-[11px] font-semibold uppercase tracking-[0.28em] text-cyan-100/70">Sesion activa</p>
                        <p class="mt-3 text-base font-semibold text-white">{{ $currentUser->name }}</p>
                        <p class="mt-1 break-all text-sm text-slate-300">{{ $currentUser->email }}</p>

                        <div class="mt-4 flex items-center justify-between gap-3">
                            <span class="rounded-full border border-white/10 bg-white/10 px-3 py-1 text-[11px] font-semibold uppercase tracking-[0.2em] text-cyan-100">
                                {{ $currentUser->role?->name ?? 'Sin rol' }}
                            </span>

                            <span class="inline-flex items-center gap-2 text-xs font-medium text-emerald-200">
                                <span class="status-dot h-2.5 w-2.5 rounded-full bg-emerald-400"></span>
                                Acceso web activo
                            </span>
                        </div>
                    </div>
                @endif
            </aside>

            <div class="relative min-w-0 lg:col-start-2">
                <header class="sticky top-0 z-30 px-4 pt-4 sm:px-6 lg:px-8">
                    <div class="app-panel enter-fade rounded-[30px] px-4 py-4 sm:px-6">
                        <div class="flex flex-wrap items-start gap-4">
                            <div class="flex min-w-0 flex-1 items-start gap-3 sm:gap-4">
                                <button type="button" class="mt-1 inline-flex h-11 w-11 items-center justify-center rounded-2xl border border-slate-200 bg-white/80 text-slate-700 shadow-sm transition hover:border-cyan-200 hover:bg-cyan-50 lg:hidden" data-admin-sidebar-toggle aria-label="Abrir menu">
                                    <svg class="h-5 w-5" viewBox="0 0 20 20" fill="none" aria-hidden="true">
                                        <path d="M4 6H16M4 10H16M4 14H11.5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" />
                                    </svg>
                                </button>

                                <div class="hidden rounded-[20px] border border-slate-200 bg-white/85 px-3 py-2 shadow-sm sm:flex lg:hidden">
                                    <x-brand.logo class="w-[150px]" />
                                </div>

                                <div class="min-w-0">
                                    <p class="text-xs font-semibold uppercase tracking-[0.34em] text-cyan-700/70">{{ $pageKicker }}</p>
                                    <h2 class="font-display mt-2 text-2xl font-semibold tracking-tight text-slate-950 sm:text-3xl">{{ $pageTitle }}</h2>
                                    <p class="mt-2 max-w-3xl text-sm leading-6 text-slate-600 sm:text-base">{{ $pageDescription }}</p>
                                </div>
                            </div>

                            <div class="ml-auto flex w-full flex-wrap items-center justify-end gap-3 sm:w-auto">
                                @yield('header-actions')

                                @if ($currentUser)
                                    <div class="hidden rounded-2xl border border-slate-200 bg-white/80 px-4 py-3 text-right shadow-sm sm:block">
                                        <p class="text-[11px] font-semibold uppercase tracking-[0.28em] text-slate-400">Usuario activo</p>
                                        <p class="mt-1 text-sm font-semibold text-slate-800">{{ $currentUser->name }}</p>
                                        <p class="mt-1 text-xs text-slate-500">{{ $currentUser->role?->name ?? 'Sin rol asignado' }}</p>
                                    </div>

                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf

                                        <button
                                            type="submit"
                                            class="inline-flex items-center gap-2 rounded-2xl border border-slate-200 bg-white/85 px-4 py-3 text-sm font-medium text-slate-700 shadow-sm transition hover:border-rose-200 hover:bg-rose-50 hover:text-rose-700"
                                        >
                                            <svg class="h-4 w-4" viewBox="0 0 20 20" fill="none" aria-hidden="true">
                                                <path d="M12.5 5.5V4.75C12.5 3.7835 11.7165 3 10.75 3H5.75C4.7835 3 4 3.7835 4 4.75V15.25C4 16.2165 4.7835 17 5.75 17H10.75C11.7165 17 12.5 16.2165 12.5 15.25V14.5" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" />
                                                <path d="M9.5 10H16M16 10L13.75 7.75M16 10L13.75 12.25" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" />
                                            </svg>
                                            Cerrar sesion
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                </header>

                @unless ($hasViteAssets)
                    <div class="px-4 pt-4 sm:px-6 lg:px-8">
                        <div class="app-panel-soft rounded-[24px] border border-amber-200/70 bg-amber-50/90 px-4 py-3 text-sm leading-6 text-amber-900">
                            Se cargo un fallback local porque Vite todavia no ha generado los assets del proyecto.
                            Ejecuta <code class="rounded bg-white/70 px-1.5 py-0.5 text-amber-950">npm install</code> y luego
                            <code class="rounded bg-white/70 px-1.5 py-0.5 text-amber-950">npm run dev</code> o
                            <code class="rounded bg-white/70 px-1.5 py-0.5 text-amber-950">npm run build</code>.
                        </div>
                    </div>
                @endunless

                @if (session('success') || session('error') || session('warning'))
                    <div class="px-4 pt-4 sm:px-6 lg:px-8">
                        @if (session('success'))
                            <div class="app-panel-soft rounded-[24px] border border-emerald-200/70 bg-emerald-50/90 px-4 py-3 text-sm leading-6 text-emerald-900">
                                {{ session('success') }}
                            </div>
                        @endif

                        @if (session('error'))
                            <div class="app-panel-soft mt-3 rounded-[24px] border border-rose-200/70 bg-rose-50/90 px-4 py-3 text-sm leading-6 text-rose-900">
                                {{ session('error') }}
                            </div>
                        @endif

                        @if (session('warning'))
                            <div class="app-panel-soft mt-3 rounded-[24px] border border-amber-200/70 bg-amber-50/90 px-4 py-3 text-sm leading-6 text-amber-900">
                                {{ session('warning') }}
                            </div>
                        @endif
                    </div>
                @endif

                <main class="px-4 pb-8 pt-6 sm:px-6 lg:px-8">
                    @yield('content')
                </main>
            </div>
        </div>

        @unless ($hasViteAssets)
            @include('layouts.partials.admin-fallback-script')
        @endunless
    </body>
</html>
