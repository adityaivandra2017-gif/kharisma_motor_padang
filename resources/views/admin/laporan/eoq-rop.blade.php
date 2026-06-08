@extends('layouts.admin')

@php
    use App\Services\Admin\LaporanEoqRopService;

    $statusOptions = [
        LaporanEoqRopService::FILTER_SEMUA => 'Semua',
        LaporanEoqRopService::FILTER_AMAN => 'Aman',
        LaporanEoqRopService::FILTER_PERLU_RESTOCK => 'Perlu Restock',
    ];

    $hasFilter = $search !== '' || $status !== LaporanEoqRopService::FILTER_SEMUA;

    $printQuery = array_filter([
        'search' => $search !== '' ? $search : null,
        'status' => $status !== LaporanEoqRopService::FILTER_SEMUA ? $status : null,
    ]);

    $thClass = 'whitespace-nowrap px-3 py-3 text-center align-middle text-xs font-semibold uppercase tracking-wide text-slate-600';
    $tdClass = 'whitespace-nowrap px-3 py-3 text-center align-middle text-sm text-slate-700';
    $tdLabelClass = 'whitespace-nowrap px-3 py-3 text-center align-middle text-sm font-medium text-slate-900';
@endphp

@section('page-title', 'Laporan Analisis EOQ & ROP')

@section('content')
    <div class="space-y-6">
        <div class="admin-section-card flex flex-col gap-4 p-5 sm:flex-row sm:items-start sm:justify-between sm:p-6">
            <div class="min-w-0">
                <p class="text-xs font-semibold uppercase tracking-wider text-rose-800/80">Laporan</p>
                <h2 class="mt-1 text-xl font-bold text-slate-900 sm:text-2xl">Analisis EOQ & ROP</h2>
                <p class="mt-1 text-sm text-slate-500">Hasil analisis Economic Order Quantity dan Reorder Point untuk mendukung keputusan pembelian onderdil.</p>
                <p class="mt-2 text-xs text-slate-400">
                    Tanggal cetak:
                    <span class="font-medium text-slate-600">{{ $tanggalCetak }}</span>
                </p>
            </div>
            <a
                href="{{ route('admin.laporan.eoq-rop.pdf', $printQuery) }}"
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

        @include('partials.admin.laporan-eoq-rop-ringkasan', ['ringkasan' => $ringkasan])

        <div class="admin-table-card admin-table-card--dropdown-safe">
            <div class="admin-table-toolbar admin-table-toolbar--filters">
                <form method="GET" action="{{ route('admin.laporan.eoq-rop') }}" class="flex flex-col gap-3 lg:flex-row lg:items-end">
                    <div class="flex-1">
                        <label for="search" class="mb-1 block text-xs font-semibold text-slate-600">Cari kode / nama onderdil</label>
                        <input
                            id="search"
                            type="search"
                            name="search"
                            value="{{ $search }}"
                            placeholder="Ketik kode atau nama onderdil..."
                            class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm outline-none transition focus:border-rose-500 focus:ring-4 focus:ring-rose-100"
                        >
                    </div>
                    <div class="w-full lg:w-52">
                        <x-admin.filter-select
                            name="status"
                            label="Status"
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
                        @if ($hasFilter)
                            <a
                                href="{{ route('admin.laporan.eoq-rop') }}"
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
                            <th class="{{ $thClass }}">Kode Onderdil</th>
                            <th class="{{ $thClass }} text-left">Nama Onderdil</th>
                            <th class="{{ $thClass }}">EOQ</th>
                            <th class="{{ $thClass }}">ROP</th>
                            <th class="{{ $thClass }}">Stok Saat Ini</th>
                            <th class="{{ $thClass }}">Status</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white">
                        @forelse ($analisis as $row)
                            @php
                                $liveStatus = $laporanService->resolveStatus(
                                    (int) $row->stok_saat_ini,
                                    (int) $row->hasil_rop,
                                );
                                $statusClass = $laporanService->statusBadgeClasses($liveStatus);
                            @endphp
                            <tr class="transition hover:bg-rose-50/30">
                                <td class="{{ $tdClass }} text-slate-500">
                                    {{ $analisis->firstItem() + $loop->index }}
                                </td>
                                <td class="{{ $tdLabelClass }}">{{ $row->kode_onderdil }}</td>
                                <td class="{{ $tdClass }} text-left">
                                    <span class="cell-truncate" title="{{ $row->nama_onderdil }}">{{ $row->nama_onderdil }}</span>
                                </td>
                                <td class="{{ $tdLabelClass }}">{{ number_format($row->hasil_eoq, 0, ',', '.') }}</td>
                                <td class="{{ $tdLabelClass }}">{{ number_format($row->hasil_rop, 0, ',', '.') }}</td>
                                <td class="{{ $tdLabelClass }}">{{ number_format($row->stok_saat_ini, 0, ',', '.') }}</td>
                                <td class="{{ $tdClass }}">
                                    <span class="inline-flex items-center whitespace-nowrap rounded-full px-2.5 py-1 text-xs font-semibold ring-1 {{ $statusClass }}">
                                        {{ $liveStatus }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-4 py-12 text-center text-slate-500">
                                    Tidak ada data analisis EOQ & ROP yang sesuai filter.
                                    <span class="mt-1 block text-xs text-slate-400">Pastikan onderdil sudah memiliki perhitungan EOQ dan ROP.</span>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($analisis->hasPages())
                <div class="admin-table-footer">
                    {{ $analisis->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
