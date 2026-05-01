@php
    $hasViteAssets = file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot'));
    $pageTitle = trim($__env->yieldContent('title', 'Equipos Biomedicos y Servicios'));
    $metaDescription = trim($__env->yieldContent('meta-description', 'Venta de equipos biomedicos, mantenimiento preventivo y correctivo con una presentacion comercial alineada al sistema.'));
    $companyName = trim($__env->yieldContent('company-name', 'Equipos Biomedicos y Servicios'));
    $homeUrl = route('home');
    $siteNavigation = [
        ['label' => 'Inicio', 'href' => $homeUrl . '#inicio'],
        ['label' => 'Servicios', 'href' => $homeUrl . '#servicios'],
        ['label' => 'Portafolio', 'href' => $homeUrl . '#portafolio'],
        ['label' => 'Mantenimiento', 'href' => $homeUrl . '#mantenimiento'],
    ];
@endphp

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="{{ $metaDescription }}">

        <title>{{ $pageTitle }} | {{ config('app.name', 'Laravel') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700,800|sora:500,600,700" rel="stylesheet" />

        @if ($hasViteAssets)
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @else
            @include('layouts.partials.admin-browser-tailwind')
        @endif
    </head>
    <body class="site-body antialiased text-slate-900">
        <div aria-hidden="true" class="pointer-events-none fixed inset-0 overflow-hidden">
            <div class="site-grid absolute inset-0 opacity-70"></div>
            <div class="float-orb absolute -left-24 top-24 h-80 w-80 rounded-full bg-cyan-300/30 blur-3xl"></div>
            <div class="float-orb absolute right-0 top-16 h-96 w-96 rounded-full bg-emerald-300/24 blur-3xl [animation-delay:1.1s]"></div>
            <div class="float-orb absolute bottom-0 left-1/3 h-72 w-72 rounded-full bg-sky-200/30 blur-3xl [animation-delay:1.8s]"></div>
        </div>

        <div class="relative min-h-screen">
            <x-site.header :navigation="$siteNavigation" :company-name="$companyName" />

            <main>
                @yield('content')
            </main>

            <x-site.footer :navigation="$siteNavigation" :company-name="$companyName" />
        </div>

        @unless ($hasViteAssets)
            @include('layouts.partials.admin-fallback-script')
        @endunless
    </body>
</html>
