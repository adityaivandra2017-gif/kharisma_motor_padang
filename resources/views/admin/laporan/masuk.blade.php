@extends('layouts.admin')

@php
    $hasFilter = $search !== '' || $tanggalAwal !== '' || $tanggalAkhir !== '';

    $printQuery = array_filter([
        'search' => $search !== '' ? $search : null,
        'tanggal_awal' => $tanggalAwal !== '' ? $tanggalAwal : null,
        'tanggal_akhir' => $tanggalAkhir !== '' ? $tanggalAkhir : null,
    ]);

    $thClass = 'whitespace-nowrap px-3 py-3 text-center align-middle text-xs font-semibold uppercase tracking-wide text-slate-600';
    $tdClass = 'whitespace-nowrap px-3 py-3 text-center align-middle text-sm text-slate-700';
    $tdLabelClass = 'whitespace-nowrap px-3 py-3 text-center align-middle text-sm font-medium text-slate-900';
@endphp

@section('page-title', 'Laporan Barang Masuk')

@section('content')
    <div class="space-y-6">
        <div class="admin-section-card flex flex-col gap-4 p-5 sm:flex-row sm:items-start sm:justify-between sm:p-6">
            <div class="min-w-0">
                <p class="text-xs font-semibold uppercase tracking-wider text-rose-800/80">Laporan</p>
                <h2 class="mt-1 text-xl font-bold text-slate-900 sm:text-2xl">Barang Masuk</h2>
                <p class="mt-1 text-sm text-slate-500">Rekap transaksi barang masuk onderdil. Dapat difilter dan dicetak ke PDF.</p>
                <p class="mt-2 text-xs text-slate-400">
                    Tanggal cetak:
                    <span class="font-medium text-slate-600">{{ $tanggalCetak }}</span>
                </p>
            </div>
            <a
                href="{{ route('admin.laporan.masuk.pdf', $printQuery) }}"
                target="_blank"
                rel="noopener"
                class="inline-flex shrink-0 items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-rose-900 to-rose-800 px-4 py-2.5 text-sm font-semibold text-white shadow-md shadow-rose-900/25 transition hover:from-rose-800 hover:to-rose-700"
            >
                <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m.75 12 3 3m0 0 3-3m-3 3v-6m-1.5-9H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                </svg>
                Cetak PDF
            </a>
        </div>

        <div class="grid grid-cols-1 gap-3 sm:grid-cols-3 sm:gap-4">
            <div class="laporan-stat-card">
                <p class="laporan-stat-card__label">Total Transaksi</p>
                <p class="laporan-stat-card__value">{{ number_format($ringkasan['total_transaksi'], 0, ',', '.') }}</p>
            </div>
            <div class="laporan-stat-card laporan-stat-card--aman">
                <p class="laporan-stat-card__label">Total Unit Masuk</p>
                <p class="laporan-stat-card__value">{{ number_format($ringkasan['total_unit'], 0, ',', '.') }}</p>
            </div>
            <div class="laporan-stat-card laporan-stat-card--menipis">
                <p class="laporan-stat-card__label">Supplier Aktif</p>
                <p class="laporan-stat-card__value">{{ number_format($ringkasan['total_supplier_aktif'], 0, ',', '.') }}</p>
            </div>
        </div>

        <div class="admin-table-card admin-table-card--dropdown-safe">
            <div class="admin-table-toolbar admin-table-toolbar--filters">
                <form method="GET" action="{{ route('admin.laporan.masuk') }}" class="flex flex-col gap-3 xl:flex-row xl:items-end">
                    <div class="flex-1">
                        <label for="search" class="mb-1 block text-xs font-semibold text-slate-600">Cari kode / nama onderdil / supplier</label>
                        <input
                            id="search"
                            type="search"
                            name="search"
                            value="{{ $search }}"
                            placeholder="Ketik kode, nama onderdil, atau supplier..."
                            class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm outline-none transition focus:border-rose-500 focus:ring-4 focus:ring-rose-100"
                        >
                    </div>
                    <div class="w-full xl:w-48">
                        <x-admin.date-picker
                            name="tanggal_awal"
                            label="Tanggal awal"
                            :value="$tanggalAwal"
                            placeholder="dd/mm/yyyy"
                        />
                    </div>
                    <div class="w-full xl:w-48">
                        <x-admin.date-picker
                            name="tanggal_akhir"
                            label="Tanggal akhir"
                            :value="$tanggalAkhir"
                            placeholder="dd/mm/yyyy"
                        />
                    </div>
                    <div class="flex flex-wrap gap-2">
                        <button
                            type="submit"
                            class="inline-flex items-center justify-center rounded-xl border border-rose-200 bg-rose-50 px-4 py-2.5 text-sm font-semibold text-rose-900 transition hover:bg-rose-100"
                        >
                            Terapkan Filter
                        </button>
                        @if ($hasFilter)
                            <a
                                href="{{ route('admin.laporan.masuk') }}"
                                class="inline-flex items-center justify-center rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm font-medium text-slate-700 transition hover:bg-slate-50"
                            >
                                Reset
                            </a>
                        @endif
                    </div>
                </form>
            </div>

            <div class="data-table-scroll">
                <table class="w-full text-sm">
                    <thead>
                        <tr>
                            <th class="{{ $thClass }} w-12">No</th>
                            <th class="{{ $thClass }}">Tanggal Masuk</th>
                            <th class="{{ $thClass }}">Kode</th>
                            <th class="{{ $thClass }} text-left">Nama Onderdil</th>
                            <th class="{{ $thClass }} text-left">Supplier</th>
                            <th class="{{ $thClass }}">Jumlah Masuk</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white">
                        @forelse ($barangMasuks as $masuk)
                            <tr class="transition hover:bg-rose-50/30">
                                <td class="{{ $tdClass }} text-slate-500">
                                    {{ $barangMasuks->firstItem() + $loop->index }}
                                </td>
                                <td class="{{ $tdLabelClass }}">{{ $masuk->tanggal_masuk->format('d-m-Y') }}</td>
                                <td class="{{ $tdLabelClass }}">{{ $masuk->onderdil?->kode_onderdil ?? '-' }}</td>
                                <td class="{{ $tdClass }} text-left">
                                    <span class="cell-truncate" title="{{ $masuk->onderdil?->nama_onderdil }}">{{ $masuk->onderdil?->nama_onderdil ?? '-' }}</span>
                                </td>
                                <td class="{{ $tdClass }} text-left">
                                    <span class="cell-truncate" title="{{ $masuk->supplier?->nama_supplier }}">{{ $masuk->supplier?->nama_supplier ?? '-' }}</span>
                                </td>
                                <td class="{{ $tdLabelClass }}">{{ number_format($masuk->jumlah, 0, ',', '.') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-4 py-12 text-center text-slate-500">
                                    Tidak ada data barang masuk yang sesuai filter.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($barangMasuks->hasPages())
                <div class="admin-table-footer">
                    {{ $barangMasuks->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
