@props([
    'panelLabel' => 'Panel Admin',
    'sidebarOpenId' => 'adminSidebarOpen',
    'notificationPrefix' => 'admin',
])

<header class="sticky top-0 z-30 border-b border-rose-100/90 bg-white/95 shadow-sm shadow-rose-900/5 backdrop-blur-xl">
    <div class="flex items-center justify-between gap-4 px-4 py-3.5 sm:px-6 lg:px-8">
        <div class="flex min-w-0 flex-1 items-center gap-3">
            <button
                id="{{ $sidebarOpenId }}"
                type="button"
                class="inline-flex h-10 w-10 shrink-0 items-center justify-center rounded-xl border border-slate-200 bg-white text-slate-700 shadow-sm transition hover:border-rose-200 hover:bg-rose-50 hover:text-rose-900 lg:hidden"
                aria-label="Buka menu navigasi"
            >
                @include('partials.admin.icon', ['name' => 'menu', 'class' => 'h-5 w-5'])
            </button>

            <div class="min-w-0 border-l-2 border-rose-200/70 pl-3">
                <span class="inline-flex items-center rounded-full bg-gradient-to-r from-rose-50 to-rose-100/80 px-2.5 py-0.5 text-[10px] font-bold uppercase tracking-[0.2em] text-rose-900 ring-1 ring-rose-200/60">
                    {{ $panelLabel }}
                </span>
                <h1 class="mt-1.5 truncate text-lg font-bold tracking-tight text-slate-900 sm:text-xl">
                    @yield('page-title', 'Dashboard')
                </h1>
            </div>
        </div>

        @include('partials.shared.notification', ['prefix' => $notificationPrefix])
    </div>
</header>
