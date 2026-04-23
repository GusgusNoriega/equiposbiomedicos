@php
    $brand = config('admin.brand', []);
    $logo = $brand['logo'] ?? [];
    $path = $logo['path'] ?? null;
@endphp

@if (filled($path))
    <img
        src="{{ asset($path) }}"
        alt="{{ $logo['alt'] ?? $brand['name'] ?? config('app.name', 'Laravel') }}"
        width="{{ $logo['width'] ?? 828 }}"
        height="{{ $logo['height'] ?? 319 }}"
        loading="eager"
        decoding="async"
        {{ $attributes->class('block h-auto') }}
    >
@else
    <span {{ $attributes->class('font-display block text-lg font-semibold tracking-tight text-slate-950') }}>
        {{ $brand['name'] ?? config('app.name', 'Laravel') }}
    </span>
@endif
