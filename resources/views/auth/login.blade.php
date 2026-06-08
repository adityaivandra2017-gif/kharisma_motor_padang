@extends('layouts.auth')

@section('content')
<section class="mx-auto flex min-h-screen w-full max-w-7xl items-center px-4 py-8 sm:px-6 lg:px-8">
    <div class="grid w-full grid-cols-1 overflow-hidden rounded-3xl border border-slate-200 bg-white/90 shadow-2xl backdrop-blur lg:grid-cols-2">
        {{-- Panel kiri: hanya tampil di mobile (di atas form) --}}
        <div class="relative bg-gradient-to-br from-slate-900 via-red-900 to-rose-900 px-6 py-8 text-white lg:hidden">
            <div class="flex flex-col items-center text-center">
                <img
                    src="{{ asset('images/logo kharisma motor.png') }}"
                    alt="Logo Kharisma"
                    class="h-28 w-auto max-w-[85%] object-contain sm:h-32"
                >
                <div class="mt-5 space-y-1.5">
                    <h1 class="text-2xl font-semibold leading-tight text-white sm:text-3xl">
                        Inventory Onderdil
                    </h1>
                    <p class="text-sm text-white/90">Dukung keputusan pembelian berbasis EOQ dan ROP.</p>
                </div>
            </div>
        </div>

        {{-- Panel kiri: hanya tampil di desktop --}}
        <div class="relative hidden bg-gradient-to-br from-slate-900 via-red-900 to-rose-900 p-10 text-white lg:flex lg:flex-col lg:justify-between">
            <div class="flex items-center gap-3 -ml-3 -mt-15">
                <img
                    src="{{ asset('images/logo kharisma motor.png') }}"
                    alt="Logo Kharisma"
                    class="h-50 w-auto"
                >
            </div>

            <div class="space-y-2 text-sm">
                <h1 class="text-3xl font-semibold leading-tight text-white">
                    Inventory Onderdil
                </h1>
                <p class="text-white/90">Dukung keputusan pembelian berbasis EOQ dan ROP.</p>
            </div>
        </div>

        <div class="p-6 sm:p-10 lg:p-12">
            <div class="mx-auto w-full max-w-md">
                <div class="mb-8">
                    <h2 class="text-2xl font-bold tracking-tight text-slate-900 sm:text-3xl">
                        Selamat Datang
                    </h2>
                    <p class="mt-2 text-sm text-slate-500">
                        Silakan login menggunakan akun internal Anda.
                    </p>
                </div>

                @if ($errors->any())
                    <div class="mb-4 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                        {{ $errors->first() }}
                    </div>
                @endif

                <form id="loginForm" action="{{ route('login.attempt') }}" method="POST" class="space-y-5" autocomplete="off">
                    @csrf

                    <div>
                        <label for="email" class="mb-2 block text-sm font-medium text-slate-700">Email</label>
                        <input
                            id="email"
                            name="email"
                            type="email"
                            placeholder="Masukkan email"
                            autocomplete="username"
                            class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm shadow-sm outline-none transition focus:border-rose-600 focus:ring-4 focus:ring-rose-100"
                            value="{{ old('email') }}"
                            required
                        >
                    </div>

                    <div>
                        <label for="password" class="mb-2 block text-sm font-medium text-slate-700">Password</label>
                        <div class="relative">
                            <input
                                id="password"
                                name="password"
                                type="password"
                                placeholder="Masukkan password"
                                autocomplete="current-password"
                                class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 pr-11 text-sm shadow-sm outline-none transition focus:border-rose-600 focus:ring-4 focus:ring-rose-100"
                                required
                            >
                            <button
                                id="togglePassword"
                                type="button"
                                class="absolute inset-y-0 right-0 inline-flex items-center px-3 text-slate-500 transition hover:text-slate-700 focus:outline-none"
                                aria-label="Tampilkan password"
                            >
                                <svg id="eyeOpenIcon" xmlns="http://www.w3.org/2000/svg" class="hidden h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7Z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                </svg>
                                <svg id="eyeClosedIcon" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 3l18 18" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.584 10.587A2 2 0 0 0 12 14a2 2 0 0 0 1.414-.586" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.364 5.365A9.466 9.466 0 0 1 12 5c4.477 0 8.268 2.943 9.542 7a9.715 9.715 0 0 1-4.126 5.124M6.228 6.228A9.71 9.71 0 0 0 2.458 12c1.274 4.057 5.065 7 9.542 7a9.46 9.46 0 0 0 5.182-1.538" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <div class="flex items-center gap-3">
                        <label class="inline-flex cursor-pointer items-center gap-2 text-sm text-slate-600">
                            <input id="remember" type="checkbox" name="remember" class="h-4 w-4 rounded border-slate-300 text-rose-700 focus:ring-rose-500">
                            Simpan Sandi
                        </label>
                    </div>

                    <button
                        type="submit"
                        class="inline-flex w-full items-center justify-center rounded-xl bg-rose-900 px-4 py-3 text-sm font-semibold text-white shadow-lg shadow-rose-900/30 transition hover:-translate-y-0.5 hover:bg-rose-800 focus:outline-none focus:ring-4 focus:ring-rose-200"
                    >
                        Login
                    </button>
                </form>

            </div>
        </div>
    </div>
</section>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const form = document.getElementById('loginForm');
        const emailInput = document.getElementById('email');
        const passwordInput = document.getElementById('password');
        const rememberInput = document.getElementById('remember');
        const togglePasswordButton = document.getElementById('togglePassword');
        const eyeOpenIcon = document.getElementById('eyeOpenIcon');
        const eyeClosedIcon = document.getElementById('eyeClosedIcon');
        const storageKey = 'kharisma_login_credentials';
        const hasErrors = @json($errors->any());

        const saved = localStorage.getItem(storageKey);
        if (saved) {
            try {
                const parsed = JSON.parse(saved);
                emailInput.value = parsed.email ?? '';
                passwordInput.value = parsed.password ?? '';
                rememberInput.checked = true;
            } catch (error) {
                localStorage.removeItem(storageKey);
                rememberInput.checked = false;
                if (!hasErrors) {
                    emailInput.value = '';
                    passwordInput.value = '';
                }
            }
        } else {
            rememberInput.checked = false;
            if (!hasErrors) {
                emailInput.value = '';
                passwordInput.value = '';
            }
        }

        function syncPasswordToggleUi() {
            const isHidden = passwordInput.type === 'password';
            eyeOpenIcon.classList.toggle('hidden', isHidden);
            eyeClosedIcon.classList.toggle('hidden', !isHidden);
            togglePasswordButton.setAttribute(
                'aria-label',
                isHidden ? 'Tampilkan password' : 'Sembunyikan password'
            );
        }

        syncPasswordToggleUi();

        togglePasswordButton.addEventListener('click', () => {
            passwordInput.type = passwordInput.type === 'password' ? 'text' : 'password';
            syncPasswordToggleUi();
        });

        form.addEventListener('submit', () => {
            if (rememberInput.checked) {
                const data = {
                    email: emailInput.value,
                    password: passwordInput.value,
                };
                localStorage.setItem(storageKey, JSON.stringify(data));
            } else {
                localStorage.removeItem(storageKey);
            }
        });
    });
</script>
@endsection
