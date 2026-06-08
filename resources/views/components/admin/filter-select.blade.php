@props([
    'name' => 'status',
    'label' => '',
    'selected' => '',
    'options' => [],
])

@php
    $selectedLabel = $options[$selected] ?? $options[array_key_first($options)] ?? '';
@endphp

<div class="admin-filter-select" data-admin-select>
    @if ($label !== '')
        <span class="mb-1.5 block text-xs font-semibold text-slate-600">{{ $label }}</span>
    @endif

    <input type="hidden" name="{{ $name }}" value="{{ $selected }}" data-admin-select-input>

    <button
        type="button"
        data-admin-select-trigger
        class="admin-filter-select__trigger"
        aria-haspopup="listbox"
        aria-expanded="false"
    >
        <span data-admin-select-label class="truncate">{{ $selectedLabel }}</span>
        <svg class="admin-filter-select__chevron" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
        </svg>
    </button>

    <ul
        data-admin-select-menu
        class="admin-filter-select__menu hidden"
        role="listbox"
    >
        @foreach ($options as $value => $optionLabel)
            <li>
                <button
                    type="button"
                    role="option"
                    data-admin-select-option
                    data-value="{{ $value }}"
                    @class(['admin-filter-select__option', 'is-selected' => (string) $selected === (string) $value])
                    aria-selected="{{ (string) $selected === (string) $value ? 'true' : 'false' }}"
                >
                    {{ $optionLabel }}
                </button>
            </li>
        @endforeach
    </ul>
</div>
