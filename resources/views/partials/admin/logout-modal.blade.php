<div
    id="adminLogoutModal"
    class="fixed inset-0 z-[60] hidden items-center justify-center p-4"
    role="dialog"
    aria-modal="true"
    aria-labelledby="adminLogoutModalTitle"
    aria-hidden="true"
>
    <div
        id="adminLogoutModalBackdrop"
        class="absolute inset-0 bg-slate-950/70 opacity-0 backdrop-blur-sm transition-opacity duration-200"
        aria-hidden="true"
    ></div>

    <div
        id="adminLogoutModalPanel"
        class="relative w-full max-w-md scale-95 overflow-hidden rounded-2xl border border-slate-200/80 bg-white opacity-0 shadow-2xl shadow-slate-900/20 transition-all duration-200 sm:rounded-3xl"
    >
        <div class="bg-gradient-to-br from-slate-900 via-rose-950 to-rose-900 px-6 py-8 text-center sm:px-8">
            <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-white/10 text-rose-100 ring-1 ring-white/20">
                @include('partials.admin.icon', ['name' => 'logout', 'class' => 'h-7 w-7'])
            </div>
            <h2 id="adminLogoutModalTitle" class="mt-4 text-xl font-bold text-white sm:text-2xl">
                Konfirmasi Keluar
            </h2>
            <p class="mt-2 text-sm leading-relaxed text-rose-100/90">
                Apakah Anda yakin ingin keluar dari sistem?
            </p>
        </div>

        <div class="space-y-3 px-6 py-5 sm:px-8 sm:py-6">
            <p class="text-center text-sm text-slate-600">
                Sesi Anda akan diakhiri. Anda perlu login kembali untuk mengakses admin.
            </p>
            <div class="flex flex-col-reverse gap-3 sm:flex-row sm:justify-center">
                <button
                    id="adminLogoutCancel"
                    type="button"
                    class="inline-flex w-full items-center justify-center rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm font-semibold text-slate-700 transition hover:border-slate-400 hover:bg-slate-50 focus:outline-none focus:ring-4 focus:ring-slate-200 sm:w-auto sm:min-w-[7.5rem]"
                >
                    Batal
                </button>
                <button
                    id="adminLogoutConfirm"
                    type="button"
                    class="inline-flex w-full items-center justify-center rounded-xl bg-gradient-to-r from-rose-900 to-rose-800 px-4 py-3 text-sm font-semibold text-white shadow-lg shadow-rose-900/25 transition hover:from-rose-800 hover:to-rose-700 focus:outline-none focus:ring-4 focus:ring-rose-200 sm:w-auto sm:min-w-[7.5rem]"
                >
                    Ya, Keluar
                </button>
            </div>
        </div>
    </div>
</div>

<form id="adminLogoutForm" action="{{ route('logout') }}" method="POST" class="hidden">
    @csrf
</form>
