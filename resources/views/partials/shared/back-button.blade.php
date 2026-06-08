@props([
    'href',
    'label' => 'Kembali',
])

<a
    href="{{ $href }}"
    title="{{ $label }}"
    aria-label="{{ $label }}"
    {{ $attributes->merge([
        'class' => 'inline-flex h-10 w-10 shrink-0 items-center justify-center rounded-xl border border-slate-200/90 bg-white text-slate-600 shadow-sm transition hover:border-rose-200 hover:bg-rose-50 hover:text-rose-900 focus:outline-none focus:ring-4 focus:ring-rose-100',
    ]) }}
>
    <x-admin.icon name="arrow-left" class="h-5 w-5" />
</a>
