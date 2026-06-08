@php
    $currentRoute = request()->route()?->getName();
    $menu = config('admin-menu');

    $navLinkClass = fn (bool $active) => $active
        ? 'bg-gradient-to-r from-rose-800 to-rose-900 text-white shadow-md shadow-rose-900/20'
        : 'text-rose-950 hover:bg-rose-50';

    $subLinkClass = fn (bool $active) => $active
        ? 'border-rose-800 bg-rose-50 font-semibold text-rose-900'
        : 'border-transparent text-slate-600 hover:border-rose-200 hover:bg-rose-50 hover:text-rose-900';

    $iconWrapClass = fn (bool $active) => $active
        ? 'bg-white/15 text-white ring-white/25'
        : 'bg-rose-50 text-rose-900 ring-rose-100 group-hover:bg-rose-100';

    $groupBtnClass = fn (bool $open) => $open
        ? 'text-rose-900'
        : 'text-slate-700 hover:bg-rose-50 hover:text-rose-900';

    $groupHasActiveChild = function (array $group) use ($currentRoute): bool {
        return collect($group['children'])->contains(
            fn (array $child): bool => $currentRoute === $child['route']
        );
    };

    $dashboardItem = collect($menu['items'])->firstWhere('route', 'admin.dashboard');
    $laporanGroup = $menu['laporan'];
    $laporanOpen = $groupHasActiveChild($laporanGroup);
    $dashboardActive = $currentRoute === $dashboardItem['route'];
@endphp

<aside
    id="adminSidebar"
    class="fixed inset-y-0 left-0 z-50 flex w-[min(100%,18.5rem)] flex-col border-r border-rose-100 bg-white shadow-xl shadow-slate-200/60 transition-transform duration-300 ease-out lg:translate-x-0 -translate-x-full"
    aria-label="Navigasi admin"
>
    <div class="relative shrink-0 overflow-hidden border-b border-rose-100 bg-gradient-to-b from-white to-rose-50/40 px-3 pb-2.5 pt-2.5 sm:px-4">
        <div class="pointer-events-none absolute -right-10 -top-12 h-32 w-32 rounded-full bg-rose-200/40 blur-3xl"></div>

        <button
            id="adminSidebarClose"
            type="button"
            class="absolute right-2 top-2 z-10 inline-flex h-8 w-8 items-center justify-center rounded-lg border border-rose-200 bg-white text-rose-900 shadow-sm transition hover:bg-rose-50 lg:hidden"
            aria-label="Tutup menu"
        >
            @include('partials.admin.icon', ['name' => 'close', 'class' => 'h-4 w-4'])
        </button>

        @include('partials.shared.sidebar-brand', ['dashboardRoute' => 'admin.dashboard'])
    </div>

    <nav id="adminSidebarNav" class="admin-sidebar-scroll flex-1 space-y-1 overflow-y-auto overscroll-contain px-3 py-3">
        <a
            href="{{ route($dashboardItem['route']) }}"
            @if ($dashboardActive) data-nav-active @endif
            class="{{ $navLinkClass($dashboardActive) }} group flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium transition-colors duration-150"
        >
            <span class="{{ $iconWrapClass($dashboardActive) }} flex h-9 w-9 shrink-0 items-center justify-center rounded-lg ring-1 transition-colors duration-150">
                @include('partials.admin.icon', ['name' => $dashboardItem['icon']])
            </span>
            <span>{{ $dashboardItem['label'] }}</span>
        </a>

        @foreach ($menu['groups'] as $group)
            @php $isGroupOpen = $groupHasActiveChild($group); @endphp
            <div class="pt-1">
                <button
                    type="button"
                    data-submenu-toggle
                    aria-expanded="{{ $isGroupOpen ? 'true' : 'false' }}"
                    aria-controls="submenu-{{ $group['id'] }}"
                    class="{{ $groupBtnClass($isGroupOpen) }} flex w-full items-center gap-3 rounded-xl px-3 py-2.5 text-left text-sm font-medium transition-colors duration-150"
                >
                    <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-rose-50 text-rose-900 ring-1 ring-rose-100">
                        @include('partials.admin.icon', ['name' => $group['icon']])
                    </span>
                    <span class="flex-1">{{ $group['label'] }}</span>
                    <span data-submenu-chevron class="{{ $isGroupOpen ? 'rotate-180 text-rose-700' : 'text-rose-300' }} transition-transform duration-200">
                        @include('partials.admin.icon', ['name' => 'chevron', 'class' => 'h-4 w-4'])
                    </span>
                </button>
                <div
                    id="submenu-{{ $group['id'] }}"
                    class="{{ $isGroupOpen ? 'block' : 'hidden' }} mt-1 ml-4 space-y-0.5 border-l border-rose-200 pl-3"
                >
                    @foreach ($group['children'] as $child)
                        @php $childActive = $currentRoute === $child['route']; @endphp
                        <a
                            href="{{ route($child['route']) }}"
                            @if ($childActive) data-nav-active @endif
                            class="{{ $subLinkClass($childActive) }} block rounded-lg border-l-2 py-2 pl-3 pr-2 text-sm transition-colors duration-150"
                        >
                            {{ $child['label'] }}
                        </a>
                    @endforeach
                </div>
            </div>
        @endforeach

        <div class="pt-1">
            <button
                type="button"
                data-submenu-toggle
                aria-expanded="{{ $laporanOpen ? 'true' : 'false' }}"
                aria-controls="submenu-{{ $laporanGroup['id'] }}"
                class="{{ $groupBtnClass($laporanOpen) }} flex w-full items-center gap-3 rounded-xl px-3 py-2.5 text-left text-sm font-medium transition-colors duration-150"
            >
                <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-rose-50 text-rose-900 ring-1 ring-rose-100">
                    @include('partials.admin.icon', ['name' => $laporanGroup['icon']])
                </span>
                <span class="flex-1">{{ $laporanGroup['label'] }}</span>
                <span data-submenu-chevron class="{{ $laporanOpen ? 'rotate-180 text-rose-700' : 'text-rose-300' }} transition-transform duration-200">
                    @include('partials.admin.icon', ['name' => 'chevron', 'class' => 'h-4 w-4'])
                </span>
            </button>
            <div
                id="submenu-{{ $laporanGroup['id'] }}"
                class="{{ $laporanOpen ? 'block' : 'hidden' }} mt-1 ml-4 space-y-0.5 border-l border-rose-200 pl-3"
            >
                @foreach ($laporanGroup['children'] as $child)
                    @php $childActive = $currentRoute === $child['route']; @endphp
                    <a
                        href="{{ route($child['route']) }}"
                        @if ($childActive) data-nav-active @endif
                        class="{{ $subLinkClass($childActive) }} block rounded-lg border-l-2 py-2 pl-3 pr-2 text-sm transition-colors duration-150"
                    >
                        {{ $child['label'] }}
                    </a>
                @endforeach
            </div>
        </div>
    </nav>

    <div class="border-t border-rose-100 bg-gradient-to-b from-rose-50/50 to-rose-100/40 p-3">
        <button
            id="adminLogoutOpen"
            type="button"
            class="flex w-full items-center gap-3 rounded-xl border border-rose-800/30 bg-gradient-to-r from-rose-900 to-rose-800 px-3 py-2.5 text-sm font-semibold text-white shadow-md shadow-rose-900/25 transition hover:from-rose-800 hover:to-rose-700 hover:shadow-lg hover:shadow-rose-900/30"
        >
            <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-white/15 text-white ring-1 ring-white/25">
                @include('partials.admin.icon', ['name' => 'logout'])
            </span>
            <span>Logout</span>
        </button>
    </div>
</aside>
