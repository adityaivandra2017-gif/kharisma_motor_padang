<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Pimpinan - Kharisma Motor Padang' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-100 font-sans text-slate-800 antialiased">
    <script>if ('scrollRestoration' in history) { history.scrollRestoration = 'manual'; }</script>
    <div id="pimpinanApp" class="min-h-screen lg:pl-72">
        <div
            id="pimpinanSidebarOverlay"
            class="fixed inset-0 z-40 bg-slate-950/60 opacity-0 backdrop-blur-sm transition-opacity duration-300 pointer-events-none lg:hidden"
            aria-hidden="true"
        ></div>

        @include('partials.pimpinan.sidebar')
        @include('partials.pimpinan.logout-modal')

        <div class="flex min-h-screen flex-col">
            @include('partials.shared.panel-header', [
                'panelLabel' => 'Panel Pimpinan',
                'sidebarOpenId' => 'pimpinanSidebarOpen',
                'notificationPrefix' => 'pimpinan',
            ])

            <main class="flex-1 px-4 py-6 sm:px-6 sm:py-8 lg:px-8">
                @yield('content')
            </main>
        </div>
    </div>
    @stack('scripts')
</body>
</html>
