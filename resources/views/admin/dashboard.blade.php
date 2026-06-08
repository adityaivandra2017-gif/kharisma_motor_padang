@extends('layouts.admin')

@section('page-title', 'Dashboard')

@section('content')
    <div class="dashboard-page">
        {{-- Sapaan (tanpa efek hover mengembang) --}}
        <div class="dashboard-greeting">
            <div class="dashboard-greeting__inner">
                <p class="dashboard-greeting__eyebrow">Dashboard Monitoring Inventory</p>
                <h2 class="dashboard-greeting__title">{{ $greeting }}, {{ $userName }}</h2>
                <p class="dashboard-greeting__text">
                    Selamat datang di Sistem Inventory Onderdil Kharisma Motor.
                    Kelola persediaan onderdil dengan lebih efektif dan efisien.
                </p>
            </div>
        </div>

        {{-- Statistik --}}
        <div class="dashboard-stat-grid">
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

        {{-- Kondisi stok — ukuran & gaya sama dengan kartu di atas --}}
        <div class="dashboard-stat-grid dashboard-stat-grid--triple">
            <div class="laporan-stat-card laporan-stat-card--aman">
                <p class="laporan-stat-card__label">Stok Aman</p>
                <p class="laporan-stat-card__value">{{ number_format($stockStatus['aman'], 0, ',', '.') }}</p>
            </div>
            <div class="laporan-stat-card laporan-stat-card--menipis">
                <p class="laporan-stat-card__label">Stok Menipis</p>
                <p class="laporan-stat-card__value">{{ number_format($stockStatus['menipis'], 0, ',', '.') }}</p>
            </div>
            <div class="laporan-stat-card laporan-stat-card--habis">
                <p class="laporan-stat-card__label">Stok Habis</p>
                <p class="laporan-stat-card__value">{{ number_format($stockStatus['habis'], 0, ',', '.') }}</p>
            </div>
        </div>

        {{-- Grafik --}}
        <div class="dashboard-panel dashboard-panel--static dashboard-panel--chart">
            <div class="dashboard-panel__head">
                <h3 class="dashboard-panel__title">Grafik Kondisi Stok Onderdil</h3>
                <p class="dashboard-panel__desc">Perbandingan jumlah onderdil berdasarkan status stok saat ini.</p>
                <div class="dashboard-chart-legend" aria-hidden="true">
                    <span class="dashboard-chart-legend__item">
                        <span class="dashboard-chart-legend__dot dashboard-chart-legend__dot--aman"></span>
                        Aman
                    </span>
                    <span class="dashboard-chart-legend__item">
                        <span class="dashboard-chart-legend__dot dashboard-chart-legend__dot--menipis"></span>
                        Menipis
                    </span>
                    <span class="dashboard-chart-legend__item">
                        <span class="dashboard-chart-legend__dot dashboard-chart-legend__dot--habis"></span>
                        Habis
                    </span>
                </div>
            </div>
            <div class="dashboard-chart-body">
                <div class="dashboard-chart-canvas">
                    <canvas
                        id="dashboardStockChart"
                        data-chart='@json($stockChart)'
                        aria-label="Grafik kondisi stok onderdil"
                        role="img"
                    ></canvas>
                </div>
            </div>
        </div>

        {{-- Tabel aktivitas (tanpa efek mengembang) --}}
        <div class="dashboard-panel dashboard-panel--static">
            <div class="dashboard-panel__head">
                <h3 class="dashboard-panel__title">Aktivitas Terbaru</h3>
                <p class="dashboard-panel__desc">5 aktivitas terakhir di sistem inventory.</p>
            </div>
            <div class="dashboard-table-wrap">
                <table class="dashboard-table">
                    <thead>
                        <tr>
                            <th>Nama Onderdil</th>
                            <th>Jenis Aktivitas</th>
                            <th class="col-center">Tanggal Aktivitas</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($recentActivities as $activity)
                            <tr>
                                <td>{{ $activity['nama_onderdil'] }}</td>
                                <td>
                                    @php
                                        $badgeClass = match ($activity['jenis']) {
                                            'Barang Masuk' => 'bg-emerald-50 text-emerald-800 ring-emerald-200',
                                            'Barang Keluar' => 'bg-sky-50 text-sky-800 ring-sky-200',
                                            'Perhitungan EOQ' => 'bg-violet-50 text-violet-800 ring-violet-200',
                                            default => 'bg-rose-50 text-rose-800 ring-rose-200',
                                        };
                                    @endphp
                                    <span class="inline-flex rounded-full px-2.5 py-0.5 text-xs font-semibold ring-1 {{ $badgeClass }}">
                                        {{ $activity['jenis'] }}
                                    </span>
                                </td>
                                <td class="col-center">{{ $activity['tanggal_label'] }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="col-center py-10 text-slate-500">
                                    Belum ada aktivitas tercatat pada sistem.
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
    @vite('resources/js/admin-dashboard.js')
@endpush
