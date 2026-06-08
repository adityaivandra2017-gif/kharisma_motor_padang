@props([
    'id',
    'name',
    'required' => false,
])

<div class="admin-filter-select" data-admin-select>
    <select
        id="{{ $id }}"
        name="{{ $name }}"
        data-admin-select-native
        class="sr-only"
        tabindex="-1"
        aria-hidden="true"
        @if ($required) required @endif
    >
        {{ $slot }}
    </select>

    <button
        type="button"
        data-admin-select-trigger
        class="admin-filter-select__trigger"
        aria-haspopup="listbox"
        aria-expanded="false"
        aria-labelledby="{{ $id }}-label"
    >
        <span id="{{ $id }}-label" data-admin-select-label class="truncate"></span>
        <svg class="admin-filter-select__chevron" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
        </svg>
    </button>

    <ul
        data-admin-select-menu
        class="admin-filter-select__menu hidden"
        role="listbox"
    ></ul>
</div>
