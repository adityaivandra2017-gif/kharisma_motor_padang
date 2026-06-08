@props([
    'label' => '',
    'labelShort' => null,
    'info' => '',
])

<th {{ $attributes->merge(['class' => 'relative whitespace-nowrap px-3 py-3 text-center align-middle text-xs font-semibold uppercase tracking-wide text-slate-600']) }}>
    <details data-info-popover class="relative inline-flex justify-center text-center">
        <summary class="info-popover-trigger list-none cursor-pointer select-none [&::-webkit-details-marker]:hidden">
            <span class="inline-flex items-center justify-center gap-1 whitespace-nowrap">
                <span class="leading-tight">{{ $label }}</span>
                <span class="text-[10px] font-semibold normal-case lowercase tracking-normal text-slate-400">(i)</span>
            </span>
        </summary>
        <div
            data-info-panel
            role="tooltip"
            class="info-popover-panel"
        >
            {{ $info }}
        </div>
    </details>
</th>
