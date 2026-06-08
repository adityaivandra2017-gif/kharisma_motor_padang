@props([
    'prefix' => 'admin',
])

@php
    $isAdminPanel = ($notificationPanelMode ?? $prefix) === 'admin';
    $isPimpinanPanel = ($notificationPanelMode ?? $prefix) === 'pimpinan';
    $notifications = $stockNotifications ?? collect();
    $notificationCount = (int) ($stockNotificationCount ?? 0);
    $hasNotifications = $notifications->isNotEmpty();
@endphp

<div class="stock-notification-root relative shrink-0" id="{{ $prefix }}NotificationRoot">
    <button
        id="{{ $prefix }}NotificationToggle"
        type="button"
        class="stock-notification-bell relative inline-flex h-10 w-10 items-center justify-center rounded-xl border border-rose-200/80 bg-white text-rose-900 shadow-sm transition hover:border-rose-300 hover:bg-rose-50 focus:outline-none focus:ring-4 focus:ring-rose-100"
        aria-label="Buka notifikasi{{ $notificationCount > 0 ? " ({$notificationCount} aktif)" : '' }}"
        aria-expanded="false"
        aria-controls="{{ $prefix }}NotificationPanel"
    >
        @include('partials.admin.icon', ['name' => 'bell', 'class' => 'h-5 w-5'])

        @if ($notificationCount > 0)
            <span class="absolute -right-0.5 -top-0.5 flex h-4 min-w-4 items-center justify-center rounded-full bg-red-600 px-1 text-[9px] font-bold leading-none text-white ring-2 ring-white">
                {{ $notificationCount > 9 ? '9+' : $notificationCount }}
            </span>
        @endif
    </button>

    <div
        id="{{ $prefix }}NotificationPanel"
        class="stock-notification-dropdown pointer-events-none absolute right-0 top-full z-50 mt-2 origin-top-right scale-95 opacity-0 transition-all duration-200 ease-out"
        role="region"
        aria-label="Daftar notifikasi"
        hidden
    >
        <svg
            class="stock-notification-arrow"
            width="16"
            height="8"
            viewBox="0 0 16 8"
            fill="none"
            xmlns="http://www.w3.org/2000/svg"
            aria-hidden="true"
        >
            <path d="M0 8h16L8 0Z" fill="#fff" />
            <path d="M0 8 8 0" stroke="#e2e8f0" stroke-width="1" stroke-linecap="round" />
            <path d="M8 0 16 8" stroke="#e2e8f0" stroke-width="1" stroke-linecap="round" />
        </svg>

        <div @class([
            'stock-notification-panel',
            'stock-notification-panel--pimpinan' => $isPimpinanPanel,
        ])>
            <div class="stock-notification-panel__header">
                <div class="flex min-w-0 items-center gap-2.5">
                    <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-xl bg-gradient-to-br from-rose-900 to-rose-700 text-white shadow-sm shadow-rose-900/20 ring-1 ring-white/20">
                        @include('partials.admin.icon', ['name' => 'bell', 'class' => 'h-4 w-4'])
                    </div>
                    <div class="min-w-0">
                        <h2 class="text-[13px] font-bold tracking-tight text-slate-900">
                            {{ $isPimpinanPanel ? 'Notifikasi Persediaan' : 'Notifikasi Stok' }}
                        </h2>
                        <p class="mt-0.5 text-[10px] leading-snug text-slate-500">
                            {{ $isPimpinanPanel ? 'Monitoring berdasarkan ROP' : 'Berdasarkan perhitungan ROP' }}
                        </p>
                    </div>
                </div>
                @if ($notificationCount > 0)
                    <span class="stock-notification-panel__count">{{ $notificationCount }}</span>
                @endif
            </div>

            @if ($hasNotifications)
                <ul class="stock-notification-list max-h-[min(16rem,55vh)] overflow-y-auto overscroll-contain">
                    @foreach ($notifications as $alert)
                        @php
                            if ($isAdminPanel) {
                                $indicator = \App\Models\Rop::statusIndicator($alert->status);
                                $statusClass = \App\Models\Rop::statusBadgeClasses($alert->status);
                                $dotClass = $indicator['dot'];
                            } else {
                                $statusClass = $alert->statusBadgeClasses();
                                $dotClass = $alert->statusDotClass();
                            }
                        @endphp
                        <li>
                            @if ($isAdminPanel)
                                <a
                                    href="{{ route('admin.perhitungan.rop.edit', $alert->ropId) }}"
                                    class="stock-notification-item group"
                                >
                            @else
                                <div class="stock-notification-item stock-notification-item--static">
                            @endif
                                <span class="flex items-start justify-between gap-3">
                                    <span class="min-w-0 flex-1">
                                        <span @class([
                                            'block truncate text-[13px] font-semibold text-slate-900',
                                            'group-hover:text-rose-900' => $isAdminPanel,
                                        ])>
                                            {{ $alert->onderdilLabel() }}
                                        </span>
                                        <span class="mt-1.5 inline-flex items-center gap-1.5 rounded-full px-2 py-0.5 text-[10px] font-semibold ring-1 ring-inset {{ $statusClass }}">
                                            <span class="h-1.5 w-1.5 shrink-0 rounded-full {{ $dotClass }}"></span>
                                            {{ $alert->status }}
                                        </span>
                                    </span>
                                    <time
                                        class="shrink-0 pt-0.5 text-[10px] font-medium tabular-nums text-slate-400"
                                        datetime="{{ $alert->occurredAt->toIso8601String() }}"
                                        data-notification-time="{{ $alert->occurredAt->toIso8601String() }}"
                                    >
                                        {{ $alert->timeLabel() }}
                                    </time>
                                </span>

                                @if ($isPimpinanPanel && $alert->isStokHabis())
                                    <p class="mt-2 text-[11px] text-slate-500">Stok onderdil telah habis.</p>
                                @else
                                    <span class="mt-2 grid grid-cols-2 gap-1.5">
                                        <span class="stock-notification-metric">
                                            <span class="stock-notification-metric__label">Stok</span>
                                            <span class="stock-notification-metric__value">{{ number_format($alert->currentStock, 0, ',', '.') }}</span>
                                        </span>
                                        <span class="stock-notification-metric">
                                            <span class="stock-notification-metric__label">ROP</span>
                                            <span class="stock-notification-metric__value">{{ number_format($alert->hasilRop, 0, ',', '.') }}</span>
                                        </span>
                                    </span>
                                @endif
                            @if ($isAdminPanel)
                                </a>
                            @else
                                </div>
                            @endif
                        </li>
                    @endforeach
                </ul>

                <div class="stock-notification-panel__footer">
                    @if ($isAdminPanel)
                        <a href="{{ route('admin.perhitungan.rop') }}" class="stock-notification-panel__footer-link">
                            Lihat semua perhitungan ROP
                            <svg class="h-3.5 w-3.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                            </svg>
                        </a>
                    @else
                        <a href="{{ route('pimpinan.notifikasi.index') }}" class="stock-notification-panel__footer-link">
                            Lihat Semua Notifikasi
                            <svg class="h-3.5 w-3.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                            </svg>
                        </a>
                    @endif
                </div>
            @else
                <div class="stock-notification-empty">
                    <div class="stock-notification-empty__icon stock-notification-empty__icon--ok">
                        <span class="h-2.5 w-2.5 rounded-full bg-emerald-500" aria-hidden="true"></span>
                    </div>
                    <p class="mt-3 text-sm font-semibold text-slate-800">
                        {{ $isPimpinanPanel ? 'Tidak ada notifikasi' : 'Tidak ada notifikasi stok' }}
                    </p>
                    <p class="mt-1 text-xs leading-relaxed text-slate-500">
                        {{ $isPimpinanPanel ? 'Semua persediaan dalam kondisi aman.' : 'Semua inventory dalam kondisi aman' }}
                    </p>
                </div>
            @endif
        </div>
    </div>
</div>
