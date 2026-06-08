@props([
    'count' => 0,
])

@if ($count > 0)
    <div
        role="alert"
        {{ $attributes->merge(['class' => 'admin-alert-card']) }}
    >
        <div class="pointer-events-none absolute inset-0 bg-gradient-to-r from-rose-950/[0.03] via-amber-50/50 to-rose-100/40"></div>
        <div class="pointer-events-none absolute -right-10 -top-10 h-36 w-36 rounded-full bg-rose-300/20 blur-3xl"></div>
        <div class="pointer-events-none absolute -bottom-8 left-1/3 h-24 w-24 rounded-full bg-amber-200/25 blur-2xl"></div>

        <div class="admin-alert-inner flex flex-col gap-4 p-4 sm:flex-row sm:items-center sm:gap-5 sm:p-5">
            <div class="flex items-start gap-4 sm:items-center">
                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-gradient-to-br from-rose-900 to-rose-700 shadow-md shadow-rose-900/30 ring-1 ring-white/20">
                    <svg class="h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" />
                    </svg>
                </div>

                <div class="min-w-0 flex-1">
                    <div class="flex flex-wrap items-center gap-2">
                        <span class="inline-flex items-center rounded-full bg-rose-900/90 px-2.5 py-0.5 text-[10px] font-bold uppercase tracking-[0.16em] text-white shadow-sm">
                            Perlu Restock
                        </span>
                        <span class="inline-flex items-center rounded-full border border-rose-200/80 bg-white/80 px-2.5 py-0.5 text-xs font-bold tabular-nums text-rose-900 ring-1 ring-rose-100">
                            {{ $count }} onderdil
                        </span>
                    </div>
                    <p class="mt-2 text-sm leading-relaxed text-slate-700 sm:text-[15px]">
                        Terdapat onderdil dengan <span class="font-semibold text-rose-950">stok saat ini ≤ hasil ROP</span>.
                        Segera lakukan pemesanan ulang agar persediaan tetap aman.
                    </p>
                </div>
            </div>

            <div class="flex shrink-0 flex-col gap-2 sm:items-end">
                <a
                    href="{{ route('admin.transaksi.masuk.create') }}"
                    class="inline-flex w-full items-center justify-center gap-1.5 rounded-xl bg-gradient-to-r from-rose-900 to-rose-800 px-4 py-2.5 text-xs font-semibold text-white shadow-md shadow-rose-900/25 transition hover:from-rose-800 hover:to-rose-700 hover:shadow-lg sm:w-auto sm:min-w-[10.5rem]"
                >
                    <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                    Barang Masuk
                </a>
                <a
                    href="#rop-table"
                    class="inline-flex w-full items-center justify-center text-[11px] font-medium text-slate-500 transition hover:text-rose-800 sm:w-auto"
                >
                    Lihat di tabel bawah
                </a>
            </div>
        </div>
    </div>
@endif
