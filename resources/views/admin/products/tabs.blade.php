@php
    $currentUser = auth()->user()?->loadMissing('role.permissions');
    $tabs = collect([
        ['label' => 'Productos biomedicos', 'route' => 'admin.products.index', 'match' => 'admin.products.*', 'permission' => 'ver-productos-biomedicos'],
        ['label' => 'Categorias', 'route' => 'admin.product-categories.index', 'match' => 'admin.product-categories.*', 'permission' => 'ver-categorias-productos'],
        ['label' => 'Marcas', 'route' => 'admin.product-brands.index', 'match' => 'admin.product-brands.*', 'permission' => 'ver-marcas-productos'],
        ['label' => 'Parametros filtrables', 'route' => 'admin.product-parameters.index', 'match' => 'admin.product-parameters.*', 'permission' => 'ver-parametros-productos'],
    ])->filter(function (array $tab) use ($currentUser): bool {
        return $currentUser && $currentUser->hasPermission($tab['permission']);
    });
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
