@props([
    'name',
    'label' => '',
    'value' => '',
    'placeholder' => 'dd/mm/yyyy',
    'id' => null,
    'required' => false,
    'clearable' => null,
])

@php
    $inputId = $id ?? $name;
    $displayText = $value !== ''
        ? \Illuminate\Support\Carbon::parse($value)->format('d/m/Y')
        : '';
    $canClear = $clearable ?? ! $required;
@endphp

<div class="admin-date-picker" data-admin-date-picker data-placeholder="{{ $placeholder }}" @if (! $canClear) data-admin-date-no-clear @endif>
    @if ($label !== '')
        <span class="mb-1.5 block text-xs font-semibold text-slate-600">{{ $label }}</span>
    @endif

    <input
        type="hidden"
        id="{{ $inputId }}"
        name="{{ $name }}"
        value="{{ $value }}"
        data-admin-date-input
        @if ($required) required @endif
    >

    <div class="admin-date-picker__field">
        <input
            type="text"
            data-admin-date-text
            value="{{ $displayText }}"
            placeholder="{{ $placeholder }}"
            inputmode="numeric"
            autocomplete="off"
            spellcheck="false"
            class="admin-date-picker__text"
            aria-label="{{ $label !== '' ? $label : 'Tanggal' }}"
        >
        <button
            type="button"
            data-admin-date-toggle
            class="admin-date-picker__toggle"
            aria-haspopup="dialog"
            aria-expanded="false"
            aria-label="Buka kalender"
        >
            <svg class="admin-date-picker__icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M4.5 8.25h15M4.5 19.5h15a1.5 1.5 0 0 0 1.5-1.5V8.25a1.5 1.5 0 0 0-1.5-1.5h-15a1.5 1.5 0 0 0-1.5 1.5v9.75a1.5 1.5 0 0 0 1.5 1.5Z" />
            </svg>
        </button>
    </div>

    <div
        data-admin-date-panel
        class="admin-date-picker__panel hidden"
        role="dialog"
        aria-modal="true"
        aria-label="Pilih tanggal"
    >
        <div class="admin-date-picker__header">
            <button type="button" data-admin-date-prev class="admin-date-picker__nav" aria-label="Bulan sebelumnya">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m15.75 19.5-7.5-7.5 7.5-7.5" /></svg>
            </button>
            <span data-admin-date-month-label class="admin-date-picker__month"></span>
            <button type="button" data-admin-date-next class="admin-date-picker__nav" aria-label="Bulan berikutnya">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" /></svg>
            </button>
        </div>

        <div class="admin-date-picker__weekdays" aria-hidden="true">
            <span>Sen</span><span>Sel</span><span>Rab</span><span>Kam</span><span>Jum</span><span>Sab</span><span>Min</span>
        </div>

        <div data-admin-date-grid class="admin-date-picker__grid"></div>

        <div class="admin-date-picker__footer">
            @if ($canClear)
                <button type="button" data-admin-date-clear class="admin-date-picker__footer-btn">Hapus</button>
            @endif
            <button type="button" data-admin-date-today class="admin-date-picker__footer-btn admin-date-picker__footer-btn--primary {{ $canClear ? '' : 'ml-auto' }}">Hari Ini</button>
        </div>
    </div>
</div>
