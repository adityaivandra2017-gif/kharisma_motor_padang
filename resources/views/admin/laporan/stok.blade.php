@extends('layouts.admin')

@php
    use App\Models\Onderdil;
    use App\Services\Admin\LaporanPersediaanStokService;

    $statusOptions = [
        LaporanPersediaanStokService::FILTER_SEMUA => 'Semua',
        Onderdil::STATUS_AMAN => 'Aman',
        Onderdil::STATUS_MENIPIS => 'Menipis',
        Onderdil::STATUS_HABIS => 'Habis',
    ];

    $pdfQuery = array_filter([
        'search' => $search,
        'status' => $status !== LaporanPersediaanStokService::FILTER_SEMUA ? $status : null,
    ]);

    $thClass = 'whitespace-nowrap px-3 py-3 text-center align-middle text-xs font-semibold uppercase tracking-wide text-slate-600';
    $tdClass = 'whitespace-nowrap px-3 py-3 text-center align-middle text-sm text-slate-700';
    $tdLabelClass = 'whitespace-nowrap px-3 py-3 text-center align-middle text-sm font-medium text-slate-900';
@endphp

@section('page-title', 'Laporan Persediaan Stok')

@section('content')
    <div class="space-y-6">
        {{-- Header modul --}}
        <div class="admin-section-card flex flex-col gap-4 p-5 sm:flex-row sm:items-start sm:justify-between sm:p-6">
            <div class="min-w-0">
                <p class="text-xs font-semibold uppercase tracking-wider text-rose-800/80">Laporan</p>
                <h2 class="mt-1 text-xl font-bold text-slate-900 sm:text-2xl">Persediaan Stok</h2>
                <p class="mt-1 text-sm text-slate-500">Kondisi persediaan onderdil saat ini. Dapat difilter dan dicetak ke PDF.</p>
                <p class="mt-2 text-xs text-slate-400">
                    Tanggal cetak:
                    <span id="laporanStokTanggalCetak" class="font-medium text-slate-600">{{ $tanggalCetak }}</span>
                </p>
            </div>
            <a
                href="{{ route('admin.laporan.stok.pdf', $pdfQuery) }}"
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

        {{-- Ringkasan --}}
        <div class="grid grid-cols-2 gap-3 sm:grid-cols-4 sm:gap-4">
            <div class="laporan-stat-card">
                <p class="laporan-stat-card__label">Total Onderdil</p>
                <p class="laporan-stat-card__value">{{ number_format($ringkasan['total_onderdil'], 0, ',', '.') }}</p>
            </div>
            <div class="laporan-stat-card laporan-stat-card--aman">
                <p class="laporan-stat-card__label">Stok Aman</p>
                <p class="laporan-stat-card__value">{{ number_format($ringkasan['total_aman'], 0, ',', '.') }}</p>
            </div>
            <div class="laporan-stat-card laporan-stat-card--menipis">
                <p class="laporan-stat-card__label">Stok Menipis</p>
                <p class="laporan-stat-card__value">{{ number_format($ringkasan['total_menipis'], 0, ',', '.') }}</p>
            </div>
            <div class="laporan-stat-card laporan-stat-card--habis">
                <p class="laporan-stat-card__label">Stok Habis</p>
                <p class="laporan-stat-card__value">{{ number_format($ringkasan['total_habis'], 0, ',', '.') }}</p>
            </div>
        </div>

        {{-- Tabel --}}
        <div class="admin-table-card admin-table-card--dropdown-safe">
            <div class="admin-table-toolbar admin-table-toolbar--filters">
                <form method="GET" action="{{ route('admin.laporan.stok') }}" class="flex flex-col gap-3 lg:flex-row lg:items-end">
                    <div class="flex-1">
                        <label for="search" class="mb-1 block text-xs font-semibold text-slate-600">Cari nama onderdil</label>
                        <input
                            id="search"
                            type="search"
                            name="search"
                            value="{{ $search }}"
                            placeholder="Ketik nama onderdil..."
                            class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm outline-none transition focus:border-rose-500 focus:ring-4 focus:ring-rose-100"
                        >
                    </div>
                    <div class="w-full lg:w-52">
                        <x-admin.filter-select
                            name="status"
                            label="Status stok"
                            :selected="$status"
                            :options="$statusOptions"
                        />
                    </div>
                    <div class="flex flex-wrap gap-2">
                        <button
                            type="submit"
                            class="inline-flex items-center justify-center rounded-xl border border-rose-200 bg-rose-50 px-4 py-2.5 text-sm font-semibold text-rose-900 transition hover:bg-rose-100"
                        >
                            Terapkan Filter
                        </button>
                        @if ($search !== '' || $status !== LaporanPersediaanStokService::FILTER_SEMUA)
                            <a
                                href="{{ route('admin.laporan.stok') }}"
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
                            <th class="{{ $thClass }}">Kode</th>
                            <th class="{{ $thClass }} text-left">Nama Onderdil</th>
                            <th class="{{ $thClass }}">Jenis</th>
                            <th class="{{ $thClass }} text-left">Supplier</th>
                            <th class="{{ $thClass }}">Harga</th>
                            <th class="{{ $thClass }}">Stok</th>
                            <th class="{{ $thClass }}">Status</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white">
                        @forelse ($onderdils as $onderdil)
                            <tr class="transition hover:bg-rose-50/30">
                                <td class="{{ $tdClass }} text-slate-500">
                                    {{ $onderdils->firstItem() + $loop->index }}
                                </td>
                                <td class="{{ $tdLabelClass }}">{{ $onderdil->kode_onderdil }}</td>
                                <td class="{{ $tdClass }} text-left">
                                    <span class="cell-truncate" title="{{ $onderdil->nama_onderdil }}">{{ $onderdil->nama_onderdil }}</span>
                                </td>
                                <td class="{{ $tdClass }}">
                                    <span class="cell-truncate" title="{{ $onderdil->jenis }}">{{ $onderdil->jenis }}</span>
                                </td>
                                <td class="{{ $tdClass }} text-left">
                                    <span class="cell-truncate" title="{{ $onderdil->supplier?->nama_supplier }}">{{ $onderdil->supplier?->nama_supplier ?? '-' }}</span>
                                </td>
                                <td class="{{ $tdClass }}">Rp {{ number_format($onderdil->harga, 0, ',', '.') }}</td>
                                <td class="{{ $tdLabelClass }}">{{ number_format($onderdil->stok, 0, ',', '.') }}</td>
                                <td class="{{ $tdClass }}">
                                    @include('admin.master-data.partials.status-stok-badge', ['status' => $onderdil->status_stok])
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-4 py-12 text-center text-slate-500">
                                    Tidak ada data onderdil yang sesuai filter.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($onderdils->hasPages())
                <div class="admin-table-footer">
                    {{ $onderdils->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
