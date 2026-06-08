<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Login - Kharisma Motor Padang' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-100 text-slate-800 antialiased">
    <div class="relative min-h-screen overflow-hidden">
        <div class="pointer-events-none absolute -top-28 -left-28 h-80 w-80 rounded-full bg-rose-200/40 blur-3xl"></div>
        <div class="pointer-events-none absolute -bottom-24 -right-24 h-80 w-80 rounded-full bg-red-300/30 blur-3xl"></div>

        <main class="relative z-10">
            @yield('content')
        </main>
    </div>
</body>
</html>
