@props([
    'navigation' => [],
    'companyName' => 'Equipos Biomedicos y Servicios',
])

<footer class="px-4 pb-8 pt-6 sm:px-6 lg:px-8">
    <div class="site-shell">
        <div class="overflow-hidden rounded-[38px] bg-slate-950 text-slate-200 shadow-[0_30px_90px_rgba(2,6,23,0.25)]">
            <div class="grid gap-10 px-6 py-8 sm:px-8 lg:grid-cols-[1.15fr_0.85fr_0.9fr_0.9fr] lg:px-10 lg:py-10">
                <div>
                    <div class="inline-flex rounded-[26px] border border-white/10 bg-white/95 px-4 py-4 shadow-lg shadow-cyan-950/20">
                        <x-brand.logo class="w-[210px]" />
                    </div>

                    <p class="mt-5 text-sm font-semibold uppercase tracking-[0.3em] text-cyan-100/70">Sitio institucional</p>
                    <p class="mt-3 max-w-md text-sm leading-7 text-slate-300">
                        {{ $companyName }} presenta una portada enfocada en venta de equipos biomedicos, mantenimiento preventivo y mantenimiento correctivo.
                    </p>
                </div>

                <div>
                    <p class="text-sm font-semibold uppercase tracking-[0.28em] text-cyan-100/70">Navegacion</p>

                    <div class="mt-5 grid gap-3">
                        @foreach ($navigation as $item)
                            <a href="{{ $item['href'] }}" class="text-sm leading-6 text-slate-300 transition hover:text-white">
                                {{ $item['label'] }}
                            </a>
                        @endforeach
                    </div>
                </div>

                <div>
                    <p class="text-sm font-semibold uppercase tracking-[0.28em] text-cyan-100/70">Servicios</p>

                    <div class="mt-5 grid gap-3 text-sm leading-6 text-slate-300">
                        <p>Venta de equipos biomedicos</p>
                        <p>Mantenimiento preventivo</p>
                        <p>Mantenimiento correctivo</p>
                        <p>Presentacion de catalogo y portafolio tecnico</p>
                    </div>
                </div>

                <div class="rounded-[30px] border border-white/10 bg-white/5 p-5 backdrop-blur-sm">
                    <p class="text-sm font-semibold uppercase tracking-[0.28em] text-cyan-100/70">Portal</p>
                    <p class="mt-4 text-sm leading-7 text-slate-300">
                        La cabecera y el pie quedaron preparados para reutilizarse en futuras vistas publicas del sitio.
                    </p>

                    <div class="mt-6 grid gap-3">
                        @auth
                            <a href="{{ route('admin.dashboard') }}" class="site-button-primary justify-center">
                                Ir al panel administrativo
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="site-button-primary justify-center">
                                Ingresar al portal
                            </a>
                        @endauth

                        <a href="#servicios" class="site-button-secondary justify-center border-white/15 bg-white/10 text-white hover:border-cyan-200/40 hover:bg-white/14">
                            Revisar servicios
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>
