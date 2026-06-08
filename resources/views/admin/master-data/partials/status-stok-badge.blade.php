@props(['status'])

@php
    $classes = match ($status) {
        \App\Models\Onderdil::STATUS_HABIS => 'bg-red-100 text-red-800 ring-red-200',
        \App\Models\Onderdil::STATUS_MENIPIS => 'bg-amber-100 text-amber-800 ring-amber-200',
        default => 'bg-emerald-100 text-emerald-800 ring-emerald-200',
    };

    $label = match ($status) {
        \App\Models\Onderdil::STATUS_HABIS => 'Habis',
        \App\Models\Onderdil::STATUS_MENIPIS => 'Menipis',
        default => 'Aman',
    };
@endphp

<span class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-semibold ring-1 {{ $classes }}">
    {{ $label }}
</span>
