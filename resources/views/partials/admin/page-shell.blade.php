@props([
    'title',
    'module' => null,
    'description' => 'Halaman ini siap dikembangkan. Tambahkan form, tabel, dan logika bisnis sesuai kebutuhan modul.',
])

<div class="rounded-2xl border border-slate-200/80 bg-white p-6 shadow-sm sm:p-8">
    @if ($module)
        <p class="text-xs font-semibold uppercase tracking-wider text-rose-800/70">{{ $module }}</p>
    @endif
    <h2 class="mt-1 text-xl font-bold text-slate-900 sm:text-2xl">{{ $title }}</h2>
    <p class="mt-3 max-w-2xl text-sm leading-relaxed text-slate-600">{{ $description }}</p>
</div>
