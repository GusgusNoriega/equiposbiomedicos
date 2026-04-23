@extends('layouts.admin')

@section('title', 'Dashboard Biomédico')
@section('page-kicker', 'BioMed Ops')
@section('page-title', 'Centro de control biomédico')
@section('page-description', 'Supervisa inventario, mantenimientos, calibraciones y cumplimiento técnico desde una base responsive lista para crecer por módulos.')

@section('header-actions')
    <div class="app-chip inline-flex items-center gap-2 rounded-2xl px-4 py-3 text-sm font-medium text-slate-700 shadow-sm">
        <span class="h-2.5 w-2.5 rounded-full bg-cyan-500"></span>
        12 alertas abiertas
    </div>
@endsection

@section('content')
    @php
        $metrics = [
            [
                'label' => 'Equipos activos',
                'value' => '284',
                'detail' => 'Activos registrados con trazabilidad técnica al día.',
                'trend' => '97.4% operativos',
                'dot' => 'bg-cyan-500',
                'bar' => 'from-cyan-500 via-sky-400 to-emerald-400',
                'progress' => '97%',
            ],
            [
                'label' => 'Preventivos del mes',
                'value' => '36',
                'detail' => 'Servicios programados entre UCI, hospitalización e imagenología.',
                'trend' => '11 pendientes',
                'dot' => 'bg-amber-500',
                'bar' => 'from-amber-400 via-orange-400 to-rose-400',
                'progress' => '69%',
            ],
            [
                'label' => 'Calibraciones por vencer',
                'value' => '14',
                'detail' => 'Equipos con ventana de atención dentro de los próximos 21 días.',
                'trend' => '4 críticos',
                'dot' => 'bg-rose-500',
                'bar' => 'from-rose-400 via-pink-400 to-orange-300',
                'progress' => '42%',
            ],
            [
                'label' => 'Documentación completa',
                'value' => '92%',
                'detail' => 'Hojas de vida, manuales y certificados centralizados.',
                'trend' => 'Normativa en verde',
                'dot' => 'bg-emerald-500',
                'bar' => 'from-emerald-400 via-teal-400 to-cyan-400',
                'progress' => '92%',
            ],
        ];

        $priorityItems = [
            [
                'title' => 'Ventiladores en UCI Norte',
                'detail' => 'Programar 3 mantenimientos preventivos antes del miércoles.',
                'label' => 'Alta',
                'tone' => 'bg-rose-100 text-rose-700',
            ],
            [
                'title' => 'Monitores multiparámetro',
                'detail' => 'Validar stock de sensores y cables en hospitalización.',
                'label' => 'Media',
                'tone' => 'bg-amber-100 text-amber-700',
            ],
            [
                'title' => 'Rayos X portátil',
                'detail' => 'Subir certificado metrológico y hoja de servicio correctivo.',
                'label' => 'Crítica',
                'tone' => 'bg-cyan-100 text-cyan-700',
            ],
            [
                'title' => 'Bombas de infusión',
                'detail' => 'Revisar redistribución de 6 unidades en urgencias y cirugía.',
                'label' => 'Seguimiento',
                'tone' => 'bg-emerald-100 text-emerald-700',
            ],
        ];

        $assetMap = [
            ['name' => 'Monitoreo', 'count' => '86', 'coverage' => '98% operativo', 'accent' => 'bg-cyan-500'],
            ['name' => 'Soporte vital', 'count' => '41', 'coverage' => '94% operativo', 'accent' => 'bg-rose-500'],
            ['name' => 'Imagenología', 'count' => '18', 'coverage' => '89% operativo', 'accent' => 'bg-indigo-500'],
            ['name' => 'Laboratorio', 'count' => '52', 'coverage' => '96% operativo', 'accent' => 'bg-emerald-500'],
        ];

        $serviceWindows = [
            ['day' => '22 Abr', 'time' => '08:00', 'area' => 'Imagenología', 'detail' => 'Calibración de ecógrafos y verificación de monitores fetales.'],
            ['day' => '22 Abr', 'time' => '13:30', 'area' => 'Quirófanos', 'detail' => 'Mantenimiento preventivo de mesas quirúrgicas y lámparas cialíticas.'],
            ['day' => '23 Abr', 'time' => '09:00', 'area' => 'UCI', 'detail' => 'Chequeo funcional de ventiladores y monitores centrales.'],
        ];

        $complianceItems = [
            ['label' => 'Hojas de vida digitalizadas', 'value' => '92%', 'bar' => '92%'],
            ['label' => 'Protocolos con firma técnica', 'value' => '88%', 'bar' => '88%'],
            ['label' => 'Equipos con riesgo actualizado', 'value' => '95%', 'bar' => '95%'],
        ];
    @endphp

    <section class="grid gap-6 xl:grid-cols-[minmax(0,1.45fr)_minmax(320px,0.95fr)]">
        <article class="app-panel enter-fade relative overflow-hidden rounded-[36px] p-6 sm:p-8">
            <div class="absolute right-0 top-0 h-52 w-52 rounded-full bg-cyan-200/50 blur-3xl"></div>
            <div class="absolute bottom-0 left-10 h-32 w-32 rounded-full bg-emerald-200/45 blur-3xl"></div>

            <div class="relative">
                <span class="app-chip inline-flex items-center gap-2 rounded-full px-4 py-2 text-xs font-semibold uppercase tracking-[0.28em] text-cyan-700">
                    Layout base reusable
                </span>

                <div class="mt-6 flex flex-col gap-8 xl:flex-row xl:items-end xl:justify-between">
                    <div class="max-w-3xl">
                        <p class="text-sm font-semibold uppercase tracking-[0.34em] text-cyan-700/70">Administrador moderno</p>
                        <h1 class="font-display mt-3 text-4xl font-semibold tracking-tight text-slate-950 sm:text-5xl">
                            Diseño preparado para operar equipos biomédicos con contexto clínico real.
                        </h1>
                        <p class="mt-4 max-w-2xl text-base leading-8 text-slate-600">
                            Esta base ya contempla navegación lateral tipo acordeón, lectura cómoda en móvil y una jerarquía visual pensada para inventario, mantenimiento, tecnovigilancia y cumplimiento.
                        </p>
                    </div>

                    <div class="app-panel-soft w-full max-w-sm rounded-[30px] p-5">
                        <p class="text-sm font-medium text-slate-500">Estado general de activos críticos</p>
                        <div class="mt-4 flex items-end justify-between gap-4">
                            <div>
                                <p class="font-display text-4xl font-semibold text-slate-950">97.4%</p>
                                <p class="mt-1 text-sm text-slate-600">Disponibilidad consolidada</p>
                            </div>

                            <div class="rounded-2xl bg-cyan-50 px-3 py-2 text-right">
                                <p class="text-[11px] font-semibold uppercase tracking-[0.24em] text-cyan-700">Uptime</p>
                                <p class="mt-1 text-sm font-semibold text-slate-800">+1.8% mensual</p>
                            </div>
                        </div>

                        <div class="mt-5 h-2 rounded-full bg-slate-200">
                            <span class="block h-full rounded-full bg-gradient-to-r from-cyan-500 via-sky-400 to-emerald-400" style="width: 97.4%"></span>
                        </div>

                        <div class="mt-5 grid gap-3 sm:grid-cols-2">
                            <div class="rounded-2xl border border-slate-200 bg-white/80 p-3">
                                <p class="text-xs uppercase tracking-[0.26em] text-slate-400">Incidencias</p>
                                <p class="mt-2 text-lg font-semibold text-slate-900">08 abiertas</p>
                            </div>

                            <div class="rounded-2xl border border-slate-200 bg-white/80 p-3">
                                <p class="text-xs uppercase tracking-[0.26em] text-slate-400">Sedes</p>
                                <p class="mt-2 text-lg font-semibold text-slate-900">03 conectadas</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </article>

        <aside class="app-panel enter-fade rounded-[36px] p-6" style="animation-delay: 120ms;">
            <div class="flex items-center justify-between gap-4">
                <div>
                    <p class="text-sm font-medium text-slate-500">Prioridades del día</p>
                    <h2 class="font-display mt-2 text-2xl font-semibold tracking-tight text-slate-950">Agenda operativa</h2>
                </div>

                <span class="app-chip rounded-full px-3 py-1.5 text-xs font-semibold uppercase tracking-[0.24em] text-cyan-700">
                    {{ count($priorityItems) }} tareas
                </span>
            </div>

            <div class="mt-6 space-y-4">
                @foreach ($priorityItems as $item)
                    <article class="rounded-[24px] border border-slate-200 bg-white/85 p-4 shadow-sm">
                        <div class="flex items-start justify-between gap-3">
                            <div class="min-w-0">
                                <h3 class="text-base font-semibold text-slate-900">{{ $item['title'] }}</h3>
                                <p class="mt-2 text-sm leading-6 text-slate-600">{{ $item['detail'] }}</p>
                            </div>

                            <span class="rounded-full px-3 py-1 text-xs font-semibold {{ $item['tone'] }}">{{ $item['label'] }}</span>
                        </div>
                    </article>
                @endforeach
            </div>
        </aside>
    </section>

    <section class="mt-6 grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
        @foreach ($metrics as $metric)
            <article class="app-panel enter-fade overflow-hidden rounded-[28px] p-5" style="animation-delay: {{ 180 + ($loop->index * 90) }}ms;">
                <div class="flex items-center justify-between gap-3">
                    <span class="inline-flex items-center gap-2 rounded-full bg-slate-950/5 px-3 py-1.5 text-xs font-medium text-slate-500">
                        <span class="h-2.5 w-2.5 rounded-full {{ $metric['dot'] }}"></span>
                        {{ $metric['trend'] }}
                    </span>
                </div>

                <p class="mt-5 text-sm font-medium text-slate-500">{{ $metric['label'] }}</p>
                <p class="font-display mt-3 text-4xl font-semibold tracking-tight text-slate-950">{{ $metric['value'] }}</p>
                <p class="mt-3 text-sm leading-6 text-slate-600">{{ $metric['detail'] }}</p>

                <div class="mt-5 h-2 rounded-full bg-slate-200">
                    <span class="block h-full rounded-full bg-gradient-to-r {{ $metric['bar'] }}" style="width: {{ $metric['progress'] }}"></span>
                </div>
            </article>
        @endforeach
    </section>

    <section class="mt-6 grid gap-6 xl:grid-cols-[minmax(0,1.1fr)_minmax(0,0.9fr)]">
        <div class="grid gap-6">
            <article class="app-panel enter-fade rounded-[34px] p-6" style="animation-delay: 420ms;">
                <div class="flex flex-wrap items-center justify-between gap-3">
                    <div>
                        <p class="text-sm font-medium text-slate-500">Mapa de activos</p>
                        <h2 class="font-display mt-2 text-2xl font-semibold tracking-tight text-slate-950">Cobertura por familia de equipo</h2>
                    </div>

                    <span class="rounded-full border border-slate-200 bg-white/80 px-4 py-2 text-sm font-medium text-slate-600 shadow-sm">
                        Inventario total: 197 críticos
                    </span>
                </div>

                <div class="mt-6 grid gap-4 md:grid-cols-2">
                    @foreach ($assetMap as $asset)
                        <article class="rounded-[26px] border border-slate-200 bg-white/85 p-5 shadow-sm">
                            <div class="flex items-start justify-between gap-3">
                                <div>
                                    <p class="text-sm font-medium text-slate-500">{{ $asset['name'] }}</p>
                                    <p class="font-display mt-3 text-3xl font-semibold tracking-tight text-slate-950">{{ $asset['count'] }}</p>
                                </div>

                                <span class="mt-1 h-3 w-3 rounded-full {{ $asset['accent'] }}"></span>
                            </div>

                            <p class="mt-4 text-sm leading-6 text-slate-600">{{ $asset['coverage'] }}</p>
                        </article>
                    @endforeach
                </div>
            </article>

            <article class="app-panel enter-fade rounded-[34px] p-6" style="animation-delay: 520ms;">
                <div>
                    <p class="text-sm font-medium text-slate-500">Cumplimiento documental</p>
                    <h2 class="font-display mt-2 text-2xl font-semibold tracking-tight text-slate-950">Trazabilidad lista para auditoría</h2>
                </div>

                <div class="mt-6 space-y-4">
                    @foreach ($complianceItems as $item)
                        <div class="rounded-[24px] border border-slate-200 bg-white/85 p-4 shadow-sm">
                            <div class="flex items-center justify-between gap-3">
                                <p class="text-sm font-medium text-slate-700">{{ $item['label'] }}</p>
                                <span class="text-sm font-semibold text-slate-900">{{ $item['value'] }}</span>
                            </div>

                            <div class="mt-3 h-2 rounded-full bg-slate-200">
                                <span class="block h-full rounded-full bg-gradient-to-r from-emerald-400 via-teal-400 to-cyan-400" style="width: {{ $item['bar'] }}"></span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </article>
        </div>

        <article class="app-panel enter-fade rounded-[34px] p-6" style="animation-delay: 480ms;">
            <div class="flex flex-wrap items-center justify-between gap-3">
                <div>
                    <p class="text-sm font-medium text-slate-500">Ventanas de servicio</p>
                    <h2 class="font-display mt-2 text-2xl font-semibold tracking-tight text-slate-950">Programación inmediata</h2>
                </div>

                <span class="app-chip rounded-full px-3 py-1.5 text-xs font-semibold uppercase tracking-[0.24em] text-cyan-700">
                    Próximas 48h
                </span>
            </div>

            <div class="mt-6 space-y-4">
                @foreach ($serviceWindows as $service)
                    <article class="rounded-[26px] border border-slate-200 bg-white/85 p-5 shadow-sm">
                        <div class="flex flex-wrap items-start justify-between gap-4">
                            <div class="flex items-start gap-4">
                                <div class="rounded-[20px] bg-slate-950 px-4 py-3 text-white">
                                    <p class="text-xs font-semibold uppercase tracking-[0.26em] text-cyan-200">{{ $service['day'] }}</p>
                                    <p class="mt-1 text-lg font-semibold">{{ $service['time'] }}</p>
                                </div>

                                <div class="min-w-0">
                                    <h3 class="text-base font-semibold text-slate-900">{{ $service['area'] }}</h3>
                                    <p class="mt-2 text-sm leading-6 text-slate-600">{{ $service['detail'] }}</p>
                                </div>
                            </div>

                            <span class="rounded-full border border-cyan-100 bg-cyan-50 px-3 py-1 text-xs font-semibold uppercase tracking-[0.2em] text-cyan-700">
                                En agenda
                            </span>
                        </div>
                    </article>
                @endforeach
            </div>
        </article>
    </section>
@endsection
