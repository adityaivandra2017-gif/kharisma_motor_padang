@extends('layouts.pimpinan')

@php
    use App\Services\Pimpinan\AnalisisEoqService;

    $statusOptions = [
        AnalisisEoqService::FILTER_SEMUA => 'Semua Data',
        AnalisisEoqService::FILTER_AMAN => 'Aman',
        AnalisisEoqService::FILTER_PERLU_PEMBELIAN => 'Perlu Pembelian',
    ];

    $hasFilter = $search !== '' || $status !== AnalisisEoqService::FILTER_SEMUA;

    $thClass = 'whitespace-nowrap px-3 py-3 text-center align-middle text-xs font-semibold uppercase tracking-wide text-slate-600';
    $tdClass = 'whitespace-nowrap px-3 py-3 text-center align-middle text-sm text-slate-700';
    $tdLabelClass = 'whitespace-nowrap px-3 py-3 text-center align-middle text-sm font-medium text-slate-900';
@endphp

@section('page-title', 'Analisis EOQ')

@section('content')
    <div class="space-y-6">
        <div class="admin-section-card p-5 sm:p-6">
            <p class="text-xs font-semibold uppercase tracking-wider text-rose-800/80">Analisis Persediaan</p>
            <h2 class="mt-1 text-xl font-bold text-slate-900 sm:text-2xl">EOQ</h2>
            <p class="mt-1 text-sm text-slate-500">
                Monitoring hasil perhitungan Economic Order Quantity (EOQ) untuk mendukung keputusan pembelian onderdil.
            </p>
        </div>

        <div class="admin-section-card analisis-formula-card mx-auto w-full max-w-2xl p-4 sm:p-6">
            <p class="analisis-formula-card__heading">Rumus EOQ</p>
            <div class="analisis-formula-card__equation-block">
                <span class="analisis-formula-card__symbol">EOQ</span>
                <span class="analisis-formula-card__equals" aria-hidden="true">=</span>
                <span class="analisis-formula-card__expression">√((2 × D × S) / H)</span>
            </div>
            <ul class="analisis-formula-card__terms">
                <li>
                    <span class="analisis-formula-card__term">D</span>
                    <span class="analisis-formula-card__term-desc">Kebutuhan tahunan</span>
                </li>
                <li>
                    <span class="analisis-formula-card__term">S</span>
                    <span class="analisis-formula-card__term-desc">Biaya pemesanan</span>
                </li>
                <li>
                    <span class="analisis-formula-card__term">H</span>
                    <span class="analisis-formula-card__term-desc">Biaya penyimpanan</span>
                </li>
            </ul>
        </div>

        <div class="grid grid-cols-2 gap-3 sm:grid-cols-4 sm:gap-4">
            <div class="analisis-stat-card">
                <p class="analisis-stat-card__label">Total Data EOQ</p>
                <p class="analisis-stat-card__value">{{ number_format($ringkasan['total_data'], 0, ',', '.') }}</p>
            </div>
            <div class="analisis-stat-card">
                <p class="analisis-stat-card__label">Rata-rata Nilai EOQ</p>
                <p class="analisis-stat-card__value">{{ number_format($ringkasan['rata_rata'], 1, ',', '.') }}</p>
            </div>
            <div class="analisis-stat-card analisis-stat-card--high">
                <p class="analisis-stat-card__label">Nilai EOQ Tertinggi</p>
                <p class="analisis-stat-card__value">{{ number_format($ringkasan['tertinggi'], 0, ',', '.') }}</p>
            </div>
            <div class="analisis-stat-card analisis-stat-card--low">
                <p class="analisis-stat-card__label">Nilai EOQ Terendah</p>
                <p class="analisis-stat-card__value">{{ number_format($ringkasan['terendah'], 0, ',', '.') }}</p>
            </div>
        </div>

        <div class="admin-table-card admin-table-card--dropdown-safe">
            <div class="admin-table-toolbar admin-table-toolbar--filters">
                <form method="GET" action="{{ route('pimpinan.analisis-persediaan.eoq') }}" class="flex flex-col gap-3 lg:flex-row lg:items-end">
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
                                href="{{ route('pimpinan.analisis-persediaan.eoq') }}"
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
                            <th class="{{ $thClass }}">Kode Onderdil</th>
                            <th class="{{ $thClass }} text-left">Nama Onderdil</th>
                            <th class="{{ $thClass }}">Nilai EOQ</th>
                            <th class="{{ $thClass }}">Stok Saat Ini</th>
                            <th class="{{ $thClass }}">Status</th>
                            <th class="{{ $thClass }} text-left">Rekomendasi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white">
                        @forelse ($analisis as $row)
                            @php
                                $liveStatus = $analisisService->resolveStatus(
                                    (int) $row->stok_saat_ini,
                                    (int) $row->hasil_eoq,
                                );
                                $statusClass = $analisisService->statusBadgeClasses($liveStatus);
                                $rekomendasi = $analisisService->rekomendasi($liveStatus, (int) $row->hasil_eoq);
                            @endphp
                            <tr class="transition hover:bg-rose-50/30">
                                <td class="{{ $tdLabelClass }}">{{ $row->kode_onderdil }}</td>
                                <td class="{{ $tdClass }} text-left">{{ $row->nama_onderdil }}</td>
                                <td class="{{ $tdClass }} font-semibold tabular-nums text-slate-900">
                                    {{ number_format((int) $row->hasil_eoq, 0, ',', '.') }}
                                </td>
                                <td class="{{ $tdClass }} tabular-nums">
                                    {{ number_format((int) $row->stok_saat_ini, 0, ',', '.') }}
                                </td>
                                <td class="{{ $tdClass }}">
                                    <span class="inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 text-xs font-semibold ring-1 ring-inset {{ $statusClass }}">
                                        <span class="h-1.5 w-1.5 shrink-0 rounded-full {{ $liveStatus === AnalisisEoqService::STATUS_AMAN ? 'bg-emerald-500' : 'bg-amber-500' }}" aria-hidden="true"></span>
                                        {{ $liveStatus }}
                                    </span>
                                </td>
                                <td class="{{ $tdClass }} text-left text-slate-600">{{ $rekomendasi }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-4 py-12 text-center text-sm text-slate-500">
                                    Belum ada data analisis EOQ yang sesuai filter.
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
