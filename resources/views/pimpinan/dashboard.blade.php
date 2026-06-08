@extends('layouts.pimpinan')

@section('page-title', 'Dashboard')

@section('content')
    @php
        $stockPercent = collect($stockChart['legends'])->keyBy('label');
    @endphp
    <div class="dashboard-page">
        <div class="dashboard-greeting">
            <div class="dashboard-greeting__inner">
                <p class="dashboard-greeting__eyebrow">Dashboard Pimpinan</p>
                <h2 class="dashboard-greeting__title">{{ $greeting }}, {{ $userName }}</h2>
                <p class="dashboard-greeting__text">
                    Selamat datang di Dashboard Pimpinan Sistem Inventory Onderdil Kharisma Motor.
                </p>
            </div>
        </div>

        <div class="dashboard-metrics-stack">
        <div class="dashboard-stat-grid dashboard-stat-grid--main">
            <div class="laporan-stat-card">
                <p class="laporan-stat-card__label">Total Onderdil</p>
                <p class="laporan-stat-card__value">{{ number_format($stats['total_onderdil'], 0, ',', '.') }}</p>
            </div>
            <div class="laporan-stat-card">
                <p class="laporan-stat-card__label">Total Supplier</p>
                <p class="laporan-stat-card__value">{{ number_format($stats['total_supplier'], 0, ',', '.') }}</p>
            </div>
            <div class="laporan-stat-card">
                <p class="laporan-stat-card__label">Total Barang Masuk</p>
                <p class="laporan-stat-card__value">{{ number_format($stats['total_barang_masuk'], 0, ',', '.') }}</p>
            </div>
            <div class="laporan-stat-card">
                <p class="laporan-stat-card__label">Total Barang Keluar</p>
                <p class="laporan-stat-card__value">{{ number_format($stats['total_barang_keluar'], 0, ',', '.') }}</p>
            </div>
        </div>

        <div class="dashboard-stat-grid dashboard-stat-grid--double dashboard-stat-grid--analysis">
            <div class="laporan-stat-card">
                <p class="laporan-stat-card__label">Total Data Analisis EOQ</p>
                <p class="laporan-stat-card__value">{{ number_format($analysis['total_eoq'], 0, ',', '.') }}</p>
            </div>
            <div class="laporan-stat-card">
                <p class="laporan-stat-card__label">Total Data Analisis ROP</p>
                <p class="laporan-stat-card__value">{{ number_format($analysis['total_rop'], 0, ',', '.') }}</p>
            </div>
            <div class="laporan-stat-card laporan-stat-card--habis dashboard-analysis-restock-card">
                <p class="laporan-stat-card__label">Perlu Restock</p>
                <p class="laporan-stat-card__value">{{ number_format($analysis['total_perlu_restock'], 0, ',', '.') }}</p>
            </div>
        </div>

        <div class="dashboard-stat-grid dashboard-stat-grid--triple dashboard-stat-grid--stock">
            <div class="laporan-stat-card laporan-stat-card--aman">
                <p class="laporan-stat-card__label">Stok Aman</p>
                <p class="laporan-stat-card__value">{{ number_format($stockStatus['aman'], 0, ',', '.') }}</p>
                <p class="dashboard-stock-card__meta">{{ rtrim(rtrim(number_format($stockPercent->get('Aman')['percent'] ?? 0, 1, '.', ''), '0'), '.') }}%</p>
            </div>
            <div class="laporan-stat-card laporan-stat-card--menipis">
                <p class="laporan-stat-card__label">Stok Menipis</p>
                <p class="laporan-stat-card__value">{{ number_format($stockStatus['menipis'], 0, ',', '.') }}</p>
                <p class="dashboard-stock-card__meta">{{ rtrim(rtrim(number_format($stockPercent->get('Menipis')['percent'] ?? 0, 1, '.', ''), '0'), '.') }}%</p>
            </div>
            <div class="laporan-stat-card laporan-stat-card--habis">
                <p class="laporan-stat-card__label">Stok Habis</p>
                <p class="laporan-stat-card__value">{{ number_format($stockStatus['habis'], 0, ',', '.') }}</p>
                <p class="dashboard-stock-card__meta">{{ rtrim(rtrim(number_format($stockPercent->get('Habis')['percent'] ?? 0, 1, '.', ''), '0'), '.') }}%</p>
            </div>
        </div>
        </div>

        <div class="dashboard-grid-two">
            <div class="dashboard-panel dashboard-panel--static dashboard-panel--chart">
                <div class="dashboard-panel__head">
                    <h3 class="dashboard-panel__title">Grafik Kondisi Stok (Doughnut)</h3>
                    <p class="dashboard-panel__desc">Distribusi persentase kondisi stok onderdil.</p>
                </div>
                <div class="dashboard-chart-body dashboard-chart-body--pimpinan">
                    <div class="dashboard-doughnut-wrap">
                        <canvas
                            id="pimpinanStockDoughnut"
                            data-chart='@json($stockChart)'
                            aria-label="Grafik doughnut kondisi stok"
                            role="img"
                        ></canvas>
                    </div>
                </div>
            </div>

            <div class="dashboard-panel dashboard-panel--static">
                <div class="dashboard-panel__head">
                    <h3 class="dashboard-panel__title">Legenda Kondisi Stok</h3>
                    <p class="dashboard-panel__desc">Jumlah item dan persentase per kategori.</p>
                </div>
                <div class="dashboard-legend-list">
                    @foreach ($stockChart['legends'] as $legend)
                        <div class="dashboard-legend-item dashboard-legend-item--{{ $legend['color'] }}">
                            <p class="dashboard-legend-item__label">
                                <span>{{ $legend['dot'] }}</span>
                                <span>{{ $legend['label'] }}</span>
                            </p>
                            <p class="dashboard-legend-item__value">
                                {{ number_format($legend['value'], 0, ',', '.') }} item ({{ rtrim(rtrim(number_format($legend['percent'], 1, '.', ''), '0'), '.') }}%)
                            </p>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="dashboard-panel dashboard-panel--static">
            <div class="dashboard-panel__head">
                <h3 class="dashboard-panel__title">Persediaan yang Memerlukan Perhatian</h3>
                <p class="dashboard-panel__desc">Maksimal 5 onderdil dengan stok saat ini di bawah atau sama dengan nilai ROP.</p>
            </div>
            <div class="dashboard-table-wrap">
                <table class="dashboard-table">
                    <thead>
                        <tr>
                            <th>Nama Onderdil</th>
                            <th class="col-center">Stok Saat Ini</th>
                            <th class="col-center">Nilai ROP</th>
                            <th class="col-center">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($attentionItems as $item)
                            <tr>
                                <td>{{ $item->nama_onderdil }}</td>
                                <td class="col-center">{{ number_format($item->stok_saat_ini, 0, ',', '.') }}</td>
                                <td class="col-center">{{ number_format($item->nilai_rop, 0, ',', '.') }}</td>
                                <td class="col-center">
                                    <span class="inline-flex rounded-full bg-red-50 px-2.5 py-0.5 text-xs font-semibold text-red-800 ring-1 ring-red-200">
                                        {{ $item->status }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="col-center py-10 text-slate-500">
                                    Belum ada onderdil yang memerlukan perhatian.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    @vite('resources/js/pimpinan-dashboard.js')
@endpush
