@extends('layouts.pimpinan')

@php
    use App\Services\Pimpinan\PimpinanStockAlert;

    $thClass = 'whitespace-nowrap px-3 py-3 text-center align-middle text-xs font-semibold uppercase tracking-wide text-slate-600';
    $tdClass = 'whitespace-nowrap px-3 py-3 text-center align-middle text-sm text-slate-700';
    $tdLabelClass = 'whitespace-nowrap px-3 py-3 text-center align-middle text-sm font-medium text-slate-900';
@endphp

@section('page-title', 'Notifikasi')

@section('content')
    <div class="space-y-6">
        <div class="admin-section-card p-5 sm:p-6">
            <p class="text-xs font-semibold uppercase tracking-wider text-rose-800/80">Monitoring Persediaan</p>
            <h2 class="mt-1 text-xl font-bold text-slate-900 sm:text-2xl">Semua Notifikasi</h2>
            <p class="mt-1 text-sm text-slate-500">
                Daftar notifikasi aktif berdasarkan kondisi stok dan hasil perhitungan ROP (view only).
            </p>
        </div>

        <div class="grid grid-cols-1 gap-3 sm:grid-cols-3 sm:gap-4">
            <div class="analisis-stat-card">
                <p class="analisis-stat-card__label">Total Notifikasi Aktif</p>
                <p class="analisis-stat-card__value">{{ number_format($totalAktif, 0, ',', '.') }}</p>
            </div>
            <div class="analisis-stat-card analisis-stat-card--low">
                <p class="analisis-stat-card__label">Perlu Restock</p>
                <p class="analisis-stat-card__value">{{ number_format($totalPerluRestock, 0, ',', '.') }}</p>
            </div>
            <div class="analisis-stat-card analisis-stat-card--critical">
                <p class="analisis-stat-card__label">Stok Habis</p>
                <p class="analisis-stat-card__value">{{ number_format($totalStokHabis, 0, ',', '.') }}</p>
            </div>
        </div>

        <div class="admin-table-card">
            @if ($notifications->isNotEmpty())
                <div class="data-table-scroll">
                    <table class="w-full text-sm">
                        <thead>
                            <tr>
                                <th class="{{ $thClass }} w-12">No</th>
                                <th class="{{ $thClass }} text-left">Nama Onderdil</th>
                                <th class="{{ $thClass }}">Stok Saat Ini</th>
                                <th class="{{ $thClass }}">Nilai ROP</th>
                                <th class="{{ $thClass }}">Status</th>
                                <th class="{{ $thClass }}">Waktu Notifikasi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white">
                            @foreach ($notifications as $alert)
                                <tr class="transition hover:bg-rose-50/30">
                                    <td class="{{ $tdClass }} text-slate-500">{{ $loop->iteration }}</td>
                                    <td class="{{ $tdLabelClass }} text-left">
                                        <span class="block font-semibold text-slate-900">{{ $alert->namaOnderdil }}</span>
                                        <span class="mt-0.5 block text-xs font-normal text-slate-500">{{ $alert->kodeOnderdil }}</span>
                                    </td>
                                    <td class="{{ $tdClass }} tabular-nums">
                                        {{ number_format($alert->currentStock, 0, ',', '.') }}
                                    </td>
                                    <td class="{{ $tdClass }} tabular-nums font-semibold text-slate-900">
                                        @if ($alert->isStokHabis())
                                            —
                                        @else
                                            {{ number_format($alert->hasilRop, 0, ',', '.') }}
                                        @endif
                                    </td>
                                    <td class="{{ $tdClass }}">
                                        <span class="inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 text-xs font-semibold ring-1 ring-inset {{ $alert->statusBadgeClasses() }}">
                                            <span class="h-1.5 w-1.5 shrink-0 rounded-full {{ $alert->statusDotClass() }}" aria-hidden="true"></span>
                                            {{ $alert->status }}
                                        </span>
                                    </td>
                                    <td class="{{ $tdClass }}">
                                        <time
                                            class="text-xs font-medium text-slate-500"
                                            datetime="{{ $alert->occurredAt->toIso8601String() }}"
                                            data-notification-time="{{ $alert->occurredAt->toIso8601String() }}"
                                        >
                                            {{ $alert->timeLabel() }}
                                        </time>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="px-6 py-14 text-center">
                    <div class="stock-notification-empty__icon stock-notification-empty__icon--ok mx-auto">
                        <span class="h-2.5 w-2.5 rounded-full bg-emerald-500" aria-hidden="true"></span>
                    </div>
                    <p class="mt-4 text-sm font-semibold text-slate-800">Tidak ada notifikasi</p>
                    <p class="mt-1 text-sm text-slate-500">Semua persediaan dalam kondisi aman.</p>
                </div>
            @endif
        </div>
    </div>
@endsection
