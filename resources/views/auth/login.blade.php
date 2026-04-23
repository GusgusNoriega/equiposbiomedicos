@php
    $hasViteAssets = file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot'));
@endphp

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Iniciar sesion | {{ config('app.name', 'Laravel') }}</title>

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
            <div class="grid-mesh absolute inset-0 opacity-50"></div>
            <div class="float-orb absolute -left-24 top-10 h-72 w-72 rounded-full bg-cyan-300/40 blur-3xl"></div>
            <div class="float-orb absolute -bottom-16 right-0 h-96 w-96 rounded-full bg-emerald-300/30 blur-3xl [animation-delay:1s]"></div>
        </div>

        <main class="relative flex min-h-screen items-center justify-center px-4 py-10 sm:px-6 lg:px-8">
            <div class="grid w-full max-w-6xl gap-6 lg:grid-cols-[minmax(0,1.15fr)_minmax(380px,0.85fr)]">
                <section class="app-panel enter-fade relative overflow-hidden rounded-[36px] p-8 sm:p-10">
                    <div class="absolute right-0 top-0 h-48 w-48 rounded-full bg-cyan-200/50 blur-3xl"></div>
                    <div class="absolute bottom-0 left-10 h-36 w-36 rounded-full bg-emerald-200/40 blur-3xl"></div>

                    <div class="relative">
                        <div class="inline-flex rounded-[28px] border border-white/70 bg-white/90 p-4 shadow-lg shadow-cyan-900/10 backdrop-blur-sm">
                            <x-brand.logo class="w-full max-w-[280px]" />
                        </div>

                        <span class="app-chip mt-6 inline-flex items-center gap-2 rounded-full px-4 py-2 text-xs font-semibold uppercase tracking-[0.28em] text-cyan-700">
                            Acceso seguro
                        </span>

                        <div class="mt-8 max-w-3xl">
                            <p class="text-sm font-semibold uppercase tracking-[0.34em] text-cyan-700/70">Panel administrativo</p>
                            <h1 class="font-display mt-3 text-4xl font-semibold tracking-tight text-slate-950 sm:text-5xl">
                                Ingresa con los usuarios actuales del sistema.
                            </h1>
                            <p class="mt-4 text-base leading-8 text-slate-600">
                                Este acceso usa la misma tabla de usuarios, sus contrasenas actuales y la asignacion de roles ya existente en el proyecto.
                            </p>
                        </div>

                        <div class="mt-10 grid gap-4 sm:grid-cols-3">
                            <article class="app-panel-soft rounded-[28px] p-5">
                                <p class="text-xs font-semibold uppercase tracking-[0.24em] text-slate-400">Usuarios</p>
                                <p class="mt-3 text-xl font-semibold text-slate-950">Credenciales vigentes</p>
                                <p class="mt-2 text-sm leading-6 text-slate-600">No se creo una segunda tabla ni un proveedor de autenticacion aparte.</p>
                            </article>

                            <article class="app-panel-soft rounded-[28px] p-5">
                                <p class="text-xs font-semibold uppercase tracking-[0.24em] text-slate-400">Roles</p>
                                <p class="mt-3 text-xl font-semibold text-slate-950">Permisos aplicados</p>
                                <p class="mt-2 text-sm leading-6 text-slate-600">Los modulos administrativos ahora revisan el rol y los permisos del usuario autenticado.</p>
                            </article>

                            <article class="app-panel-soft rounded-[28px] p-5">
                                <p class="text-xs font-semibold uppercase tracking-[0.24em] text-slate-400">Sesion</p>
                                <p class="mt-3 text-xl font-semibold text-slate-950">Proteccion web</p>
                                <p class="mt-2 text-sm leading-6 text-slate-600">El acceso al panel `/admin` ya no queda expuesto para visitantes.</p>
                            </article>
                        </div>
                    </div>
                </section>

                <section class="app-panel enter-fade rounded-[36px] p-6 sm:p-8" style="animation-delay: 120ms;">
                    <div class="rounded-[28px] border border-slate-200 bg-white/85 p-4 shadow-sm">
                        <x-brand.logo class="w-full max-w-[230px]" />

                        <div class="mt-4 min-w-0">
                            <p class="text-xs font-semibold uppercase tracking-[0.28em] text-cyan-700/70">Portal administrativo</p>
                            <p class="mt-2 text-sm leading-6 text-slate-600">
                                Acceso al sistema con credenciales vigentes y permisos aplicados segun el rol asignado.
                            </p>
                        </div>
                    </div>

                    @if (session('success'))
                        <div class="mt-6 rounded-[24px] border border-emerald-200/70 bg-emerald-50/90 px-4 py-3 text-sm leading-6 text-emerald-900">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="mt-6 rounded-[24px] border border-rose-200/70 bg-rose-50/90 px-4 py-3 text-sm leading-6 text-rose-900">
                            Verifica tus credenciales e intenta nuevamente.
                        </div>
                    @endif

                    <form method="POST" action="{{ route('login.attempt') }}" class="mt-6 space-y-5">
                        @csrf

                        <div>
                            <label for="email" class="text-sm font-semibold text-slate-700">Correo electronico</label>
                            <input
                                id="email"
                                type="email"
                                name="email"
                                value="{{ old('email') }}"
                                required
                                autofocus
                                autocomplete="username"
                                class="mt-2 block w-full rounded-[22px] border border-slate-200 bg-white/90 px-4 py-3 text-sm text-slate-900 shadow-sm outline-none transition focus:border-cyan-300 focus:ring-4 focus:ring-cyan-100"
                                placeholder="tu@correo.com"
                            >
                            @error('email')
                                <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="password" class="text-sm font-semibold text-slate-700">Contrasena</label>
                            <input
                                id="password"
                                type="password"
                                name="password"
                                required
                                autocomplete="current-password"
                                class="mt-2 block w-full rounded-[22px] border border-slate-200 bg-white/90 px-4 py-3 text-sm text-slate-900 shadow-sm outline-none transition focus:border-cyan-300 focus:ring-4 focus:ring-cyan-100"
                                placeholder="Ingresa tu contrasena"
                            >
                            @error('password')
                                <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <label class="inline-flex items-center gap-3 text-sm text-slate-600">
                            <input type="checkbox" name="remember" value="1" class="h-4 w-4 rounded border-slate-300 text-cyan-600 focus:ring-cyan-200" {{ old('remember') ? 'checked' : '' }}>
                            Mantener sesion iniciada en este navegador
                        </label>

                        <button
                            type="submit"
                            class="inline-flex w-full items-center justify-center rounded-[22px] bg-slate-950 px-5 py-3.5 text-sm font-semibold text-white shadow-lg shadow-slate-900/15 transition hover:-translate-y-0.5 hover:bg-cyan-700"
                        >
                            Ingresar al panel
                        </button>
                    </form>

                    <div class="mt-6 rounded-[24px] border border-slate-200 bg-white/80 px-4 py-4 text-sm leading-6 text-slate-600">
                        Si ya tienes un usuario creado desde el modulo de usuarios, puedes iniciar sesion aqui sin cambios adicionales.
                    </div>
                </section>
            </div>
        </main>

        @unless ($hasViteAssets)
            @include('layouts.partials.admin-fallback-script')
        @endunless
    </body>
</html>
