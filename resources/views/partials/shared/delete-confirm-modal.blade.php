@props([
    'id' => 'deleteConfirmModal',
])

<div
    id="{{ $id }}"
    data-delete-modal
    class="fixed inset-0 z-[60] hidden items-center justify-center p-4"
    role="dialog"
    aria-modal="true"
    aria-labelledby="{{ $id }}Title"
    aria-hidden="true"
>
    <div
        data-delete-backdrop
        class="absolute inset-0 bg-slate-900/50 transition-opacity duration-200"
        aria-hidden="true"
    ></div>

    <div
        data-delete-panel
        class="relative w-full max-w-[20rem] scale-95 rounded-2xl border border-slate-200 bg-white opacity-0 shadow-xl transition-all duration-200"
    >
        <div class="px-5 py-5 text-center">
            <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl bg-rose-100 text-rose-600 shadow-inner shadow-rose-200/50">
                <x-shared.trash-icon class="h-10 w-10" />
            </div>

            <h2 id="{{ $id }}Title" data-delete-title class="mt-3.5 text-base font-bold text-slate-900">
                Konfirmasi Hapus
            </h2>

            <p data-delete-message class="mt-1.5 text-sm leading-relaxed text-slate-500">
                Apakah Anda yakin ingin menghapus data ini?
            </p>

            <p data-delete-subtitle class="mt-2 hidden rounded-lg bg-amber-50 px-2.5 py-1.5 text-xs leading-relaxed text-amber-800 ring-1 ring-amber-200"></p>

            <div
                data-delete-item-wrap
                class="mt-3 hidden rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-left"
            >
                <p class="text-[10px] font-semibold uppercase tracking-wide text-slate-400">Data yang dihapus</p>
                <p data-delete-item class="mt-0.5 truncate text-sm font-semibold text-slate-800"></p>
            </div>

            <div class="mt-5 grid grid-cols-2 gap-2">
                <button
                    type="button"
                    data-delete-cancel
                    class="rounded-lg border border-slate-200 px-3 py-2 text-sm font-semibold text-slate-600 transition hover:bg-slate-50"
                >
                    Batal
                </button>
                <button
                    type="button"
                    data-delete-confirm
                    class="rounded-lg bg-rose-600 px-3 py-2 text-sm font-semibold text-white transition hover:bg-rose-700"
                >
                    Ya, Hapus
                </button>
            </div>
        </div>
    </div>
</div>
