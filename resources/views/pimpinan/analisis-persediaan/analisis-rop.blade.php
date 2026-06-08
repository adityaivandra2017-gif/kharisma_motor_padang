@extends('layouts.pimpinan')

@php
    use App\Services\Pimpinan\AnalisisRopService;

    $statusOptions = [
        AnalisisRopService::FILTER_SEMUA => 'Semua Data',
        AnalisisRopService::FILTER_AMAN => 'Aman',
        AnalisisRopService::FILTER_PERLU_RESTOCK => 'Perlu Restock',
        AnalisisRopService::FILTER_STOK_KRITIS => 'Stok Kritis',
    ];

    $hasFilter = $search !== '' || $status !== AnalisisRopService::FILTER_SEMUA;

    $thClass = 'whitespace-nowrap px-3 py-3 text-center align-middle text-xs font-semibold uppercase tracking-wide text-slate-600';
    $tdClass = 'whitespace-nowrap px-3 py-3 text-center align-middle text-sm text-slate-700';
    $tdLabelClass = 'whitespace-nowrap px-3 py-3 text-center align-middle text-sm font-medium text-slate-900';
@endphp

@section('page-title', 'ROP')

@section('content')
    <div class="space-y-6">
        <div class="admin-section-card p-5 sm:p-6">
            <p class="text-xs font-semibold uppercase tracking-wider text-rose-800/80">Analisis Persediaan</p>
            <h2 class="mt-1 text-xl font-bold text-slate-900 sm:text-2xl">ROP</h2>
            <p class="mt-1 text-sm text-slate-500">
                Monitoring hasil perhitungan Reorder Point (ROP) untuk mengetahui onderdil yang perlu segera dipesan ulang.
            </p>
        </div>

        <div class="admin-section-card analisis-formula-card mx-auto w-full max-w-2xl p-4 sm:p-6">
            <p class="analisis-formula-card__heading">Rumus ROP</p>
            <div class="analisis-formula-card__equation-block">
                <span class="analisis-formula-card__symbol">ROP</span>
                <span class="analisis-formula-card__equals" aria-hidden="true">=</span>
                <span class="analisis-formula-card__expression">(Lead Time × Kebutuhan Harian) + Safety Stock</span>
            </div>
            <ul class="analisis-formula-card__terms">
                <li>
                    <span class="analisis-formula-card__term">Lead Time</span>
                    <span class="analisis-formula-card__term-desc">Waktu tunggu pemesanan (hari)</span>
                </li>
                <li>
                    <span class="analisis-formula-card__term">Kebutuhan Harian</span>
                    <span class="analisis-formula-card__term-desc">Konsumsi per hari</span>
                </li>
                <li>
                    <span class="analisis-formula-card__term">Safety Stock</span>
                    <span class="analisis-formula-card__term-desc">Stok pengaman</span>
                </li>
            </ul>
        </div>

        <div class="grid grid-cols-2 gap-3 sm:grid-cols-4 sm:gap-4">
            <div class="analisis-stat-card">
                <p class="analisis-stat-card__label">Total Data ROP</p>
                <p class="analisis-stat-card__value">{{ number_format($ringkasan['total_data'], 0, ',', '.') }}</p>
            </div>
            <div class="analisis-stat-card analisis-stat-card--high">
                <p class="analisis-stat-card__label">Total Onderdil Aman</p>
                <p class="analisis-stat-card__value">{{ number_format($ringkasan['total_aman'], 0, ',', '.') }}</p>
            </div>
            <div class="analisis-stat-card analisis-stat-card--low">
                <p class="analisis-stat-card__label">Total Perlu Restock</p>
                <p class="analisis-stat-card__value">{{ number_format($ringkasan['total_perlu_restock'], 0, ',', '.') }}</p>
            </div>
            <div class="analisis-stat-card analisis-stat-card--critical">
                <p class="analisis-stat-card__label">Total Stok Kritis</p>
                <p class="analisis-stat-card__value">{{ number_format($ringkasan['total_stok_kritis'], 0, ',', '.') }}</p>
            </div>
        </div>

        @if ($prioritasRestock->isNotEmpty())
            <div class="admin-section-card overflow-hidden p-0">
                <div class="border-b border-slate-200 bg-gradient-to-r from-rose-50/80 to-white px-5 py-4 sm:px-6">
                    <h3 class="text-sm font-bold text-slate-900 sm:text-base">Prioritas Restock</h3>
                    <p class="mt-0.5 text-xs text-slate-500">Maksimal 5 onderdil dengan stok saat ini ≤ nilai ROP (diurutkan dari stok terendah).</p>
                </div>
                <ul class="divide-y divide-slate-100">
                    @foreach ($prioritasRestock as $item)
                        <li class="flex flex-col gap-2 px-5 py-3.5 sm:flex-row sm:items-center sm:justify-between sm:px-6">
                            <p class="text-sm font-semibold text-slate-900">{{ $item->nama_onderdil }}</p>
                            <div class="flex flex-wrap gap-2 text-xs">
                                <span class="rounded-lg border border-slate-200 bg-slate-50 px-2.5 py-1 font-medium text-slate-700">
                                    Stok: <span class="tabular-nums font-bold text-slate-900">{{ number_format((int) $item->stok_saat_ini, 0, ',', '.') }}</span>
                                </span>
                                <span class="rounded-lg border border-rose-100 bg-rose-50 px-2.5 py-1 font-medium text-rose-900">
                                    ROP: <span class="tabular-nums font-bold">{{ number_format((int) $item->hasil_rop, 0, ',', '.') }}</span>
                                </span>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="admin-table-card admin-table-card--dropdown-safe">
            <div class="admin-table-toolbar admin-table-toolbar--filters">
                <form method="GET" action="{{ route('pimpinan.analisis-persediaan.rop') }}" class="flex flex-col gap-3 lg:flex-row lg:items-end">
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
                                href="{{ route('pimpinan.analisis-persediaan.rop') }}"
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
                            <th class="{{ $thClass }}">Nilai ROP</th>
                            <th class="{{ $thClass }}">Stok Saat Ini</th>
                            <th class="{{ $thClass }}">Status</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white">
                        @forelse ($analisis as $row)
                            @php
                                $liveStatus = $analisisService->resolveStatus(
                                    (int) $row->stok_saat_ini,
                                    (int) $row->hasil_rop,
                                );
                                $statusClass = $analisisService->statusBadgeClasses($liveStatus);
                                $dotClass = $analisisService->statusDotClass($liveStatus);
                            @endphp
                            <tr class="transition hover:bg-rose-50/30">
                                <td class="{{ $tdLabelClass }}">{{ $row->kode_onderdil }}</td>
                                <td class="{{ $tdClass }} text-left">{{ $row->nama_onderdil }}</td>
                                <td class="{{ $tdClass }} font-semibold tabular-nums text-slate-900">
                                    {{ number_format((int) $row->hasil_rop, 0, ',', '.') }}
                                </td>
                                <td class="{{ $tdClass }} tabular-nums">
                                    {{ number_format((int) $row->stok_saat_ini, 0, ',', '.') }}
                                </td>
                                <td class="{{ $tdClass }}">
                                    <span class="inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 text-xs font-semibold ring-1 ring-inset {{ $statusClass }}">
                                        <span class="h-1.5 w-1.5 shrink-0 rounded-full {{ $dotClass }}" aria-hidden="true"></span>
                                        {{ $liveStatus }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-12 text-center text-sm text-slate-500">
                                    Belum ada data analisis ROP yang sesuai filter.
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
