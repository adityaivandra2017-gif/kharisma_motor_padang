@extends('layouts.admin')

@section('page-title', 'Reorder Point (ROP)')

@section('content')
    <div class="space-y-6">
        @if (session('success'))
            <div class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">
                {{ session('success') }}
            </div>
        @endif

        <x-admin.restock-alert :count="$restockSoonCount" />

        <div class="admin-section-card flex flex-col gap-4 p-5 sm:flex-row sm:items-center sm:justify-between sm:p-6">
            <div class="min-w-0">
                <p class="text-xs font-semibold uppercase tracking-wider text-rose-800/80">Perhitungan</p>
                <h2 class="mt-1 text-xl font-bold text-slate-900 sm:text-2xl">Reorder Point (ROP)</h2>
                <p class="mt-1 text-sm text-slate-500">Tentukan kapan harus melakukan pemesanan ulang agar stok onderdil tidak habis.</p>
            </div>
            <a
                href="{{ route('admin.perhitungan.rop.create') }}"
                class="inline-flex shrink-0 items-center justify-center rounded-xl bg-gradient-to-r from-rose-900 to-rose-800 px-4 py-2.5 text-sm font-semibold text-white shadow-md shadow-rose-900/20 transition hover:from-rose-800 hover:to-rose-700"
            >
                + Tambah Perhitungan ROP
            </a>
        </div>

        <div class="flex justify-center">
            <div class="admin-formula-card w-full max-w-xl p-4 sm:p-5">
                <p class="text-center text-[11px] font-semibold uppercase tracking-[0.18em] text-rose-700">Rumus ROP</p>
                <p class="mt-1 text-center text-sm font-semibold text-rose-900 sm:text-base">ROP = (Lead Time × Kebutuhan/Hari) + Safety Stock</p>
            </div>
        </div>

        <div id="rop-table" class="admin-table-card scroll-mt-24">
            <div class="admin-table-toolbar">
                <form method="GET" action="{{ route('admin.perhitungan.rop') }}" class="flex flex-col gap-3 sm:flex-row">
                    <input
                        type="search"
                        name="search"
                        value="{{ $search }}"
                        placeholder="Cari onderdil, lead time, kebutuhan harian, safety stock, hasil ROP, atau status..."
                        class="w-full flex-1 rounded-xl border border-slate-300 px-4 py-2.5 text-sm outline-none transition focus:border-rose-500 focus:ring-4 focus:ring-rose-100"
                    >
                    <button
                        type="submit"
                        class="inline-flex items-center justify-center rounded-xl border border-rose-200 bg-rose-50 px-4 py-2.5 text-sm font-semibold text-rose-900 transition hover:bg-rose-100"
                    >
                        Cari
                    </button>
                    @if ($search !== '')
                        <a
                            href="{{ route('admin.perhitungan.rop') }}"
                            class="inline-flex items-center justify-center rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm font-medium text-slate-700 transition hover:bg-slate-50"
                        >
                            Reset
                        </a>
                    @endif
                </form>
            </div>

            @php
                $thClass = 'whitespace-nowrap px-3 py-3 text-center align-middle text-xs font-semibold uppercase tracking-wide text-slate-600';
                $tdClass = 'whitespace-nowrap px-3 py-3 text-center align-middle text-sm text-slate-700';
                $tdLabelClass = 'whitespace-nowrap px-3 py-3 text-center align-middle text-sm font-medium text-slate-900';
            @endphp

            <div class="calc-table-scroll">
                <table class="calc-table w-full text-sm">
                    <thead class="relative z-20">
                        <tr>
                            <th class="{{ $thClass }} text-left">Onderdil</th>
                            <x-calculation-table-header
                                label="Lead Time"
                                info="Waktu tunggu dari pemesanan sampai barang datang."
                            />
                            <x-calculation-table-header
                                label="Kebutuhan/Hari"
                                info="Rata-rata kebutuhan onderdil setiap hari."
                            />
                            <x-calculation-table-header
                                label="Safety Stock"
                                info="Stok cadangan untuk menghindari kehabisan barang."
                            />
                            <x-calculation-table-header
                                label="Hasil ROP"
                                info="Batas stok minimal sebelum harus melakukan pemesanan ulang."
                            />
                            <th class="{{ $thClass }}">Stok Saat Ini</th>
                            <th class="{{ $thClass }}">Status</th>
                            <th class="{{ $thClass }}">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white">
                        @forelse ($rops as $rop)
                            @php
                                $currentStock = (int) ($rop->onderdil?->stok ?? 0);
                                $liveStatus = \App\Models\Rop::resolveLiveStockStatus(
                                    $currentStock,
                                    (int) $rop->hasil_rop,
                                    (int) $rop->safety_stock,
                                );
                                $statusClass = \App\Models\Rop::statusBadgeClasses($liveStatus);
                            @endphp
                            <tr class="transition hover:bg-rose-50/30">
                                <td class="{{ $tdLabelClass }} text-left">
                                    <span class="cell-truncate" title="{{ $rop->onderdil?->kode_onderdil }} — {{ $rop->onderdil?->nama_onderdil }}">
                                        {{ $rop->onderdil?->kode_onderdil }} — {{ $rop->onderdil?->nama_onderdil }}
                                    </span>
                                </td>
                                <td class="{{ $tdLabelClass }}">{{ $rop->lead_time }} hari</td>
                                <td class="{{ $tdLabelClass }}">{{ number_format($rop->kebutuhan_per_hari, 0, ',', '.') }}</td>
                                <td class="{{ $tdLabelClass }}">{{ number_format($rop->safety_stock, 0, ',', '.') }}</td>
                                <td class="{{ $tdLabelClass }}">
                                    <span class="inline-flex items-center rounded-full bg-rose-50 px-3 py-1 text-xs font-semibold text-rose-900 ring-1 ring-rose-100">
                                        {{ number_format($rop->hasil_rop, 0, ',', '.') }} unit
                                    </span>
                                </td>
                                <td class="{{ $tdLabelClass }}">{{ number_format($currentStock, 0, ',', '.') }}</td>
                                <td class="{{ $tdClass }}">
                                    <span
                                        class="inline-flex items-center whitespace-nowrap rounded-full px-2.5 py-1 text-xs font-semibold ring-1 {{ $statusClass }}"
                                        title="{{ $liveStatus }}"
                                    >
                                        {{ $liveStatus }}
                                    </span>
                                </td>
                                <td class="{{ $tdClass }}">
                                    <div class="inline-flex items-center justify-center gap-2">
                                        <a
                                            href="{{ route('admin.perhitungan.rop.edit', $rop) }}"
                                            class="rounded-lg border border-slate-200 px-2.5 py-1.5 text-xs font-medium text-slate-700 transition hover:border-rose-200 hover:bg-rose-50 hover:text-rose-900"
                                        >
                                            Edit
                                        </a>
                                        <form
                                            id="delete-rop-{{ $rop->id }}"
                                            action="{{ route('admin.perhitungan.rop.destroy', $rop) }}"
                                            method="POST"
                                            class="hidden"
                                        >
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                        <button
                                            type="button"
                                            data-delete-trigger
                                            data-delete-form="delete-rop-{{ $rop->id }}"
                                            data-delete-title="Hapus Perhitungan ROP?"
                                            data-delete-message="Data perhitungan ROP berikut akan dihapus permanen."
                                            data-delete-item="{{ $rop->onderdil?->kode_onderdil }} — ROP {{ $rop->hasil_rop }} unit"
                                            class="rounded-lg border border-red-200 px-2.5 py-1.5 text-xs font-medium text-red-700 transition hover:bg-red-50"
                                        >
                                            Hapus
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-4 py-12 text-center text-slate-500">
                                    @if ($search !== '')
                                        Tidak ada data perhitungan ROP yang cocok dengan pencarian.
                                    @else
                                        Belum ada data perhitungan ROP. Klik <strong>Tambah Perhitungan ROP</strong> untuk memulai.
                                    @endif
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($rops->hasPages())
                <div class="admin-table-footer">
                    {{ $rops->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
