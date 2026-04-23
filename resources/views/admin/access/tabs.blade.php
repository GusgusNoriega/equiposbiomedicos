@php
    $tabs = [
        ['label' => 'Usuarios', 'route' => 'admin.users.index', 'match' => 'admin.users.*'],
        ['label' => 'Roles', 'route' => 'admin.roles.index', 'match' => 'admin.roles.*'],
        ['label' => 'Permisos', 'route' => 'admin.permissions.index', 'match' => 'admin.permissions.*'],
    ];
@endphp

<div class="app-panel-soft rounded-[28px] p-3">
    <div class="flex flex-wrap gap-2">
        @foreach ($tabs as $tab)
            <a
                href="{{ route($tab['route']) }}"
                @class([
                    'rounded-2xl px-4 py-3 text-sm font-medium transition',
                    'bg-slate-950 text-white shadow-lg shadow-slate-950/10' => request()->routeIs($tab['match']),
                    'bg-white/80 text-slate-600 hover:bg-cyan-50 hover:text-slate-900' => ! request()->routeIs($tab['match']),
                ])
            >
                {{ $tab['label'] }}
            </a>
        @endforeach
    </div>
</div>
