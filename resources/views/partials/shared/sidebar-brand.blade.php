@props(['dashboardRoute'])

<a href="{{ route($dashboardRoute) }}" class="group relative z-[1] flex w-full flex-col items-center text-center">
    <div class="flex h-[8rem] w-full items-start justify-center leading-none sm:h-[9rem]">
        <img
            src="{{ asset('images/logo kharisma motor.png') }}"
            alt="Kharisma Motor Padang"
            class="h-full w-full max-w-full object-contain object-top"
        >
    </div>
    <h2 class="mt-0 max-w-[15rem] px-1 text-xs font-bold leading-snug tracking-wide text-rose-950 sm:text-sm">
        Inventory Onderdil - EOQ &amp; ROP
    </h2>
    <div class="mt-1.5 h-px w-16 bg-gradient-to-r from-transparent via-rose-300/80 to-transparent"></div>
</a>
