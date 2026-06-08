@extends('layouts.admin')

@section('page-title', 'Economic Order Quantity (EOQ)')

@section('content')
    <div class="space-y-6">
        @if (session('success'))
            <div class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">
                {{ session('success') }}
            </div>
        @endif

        <div class="admin-section-card flex flex-col gap-4 p-5 sm:flex-row sm:items-center sm:justify-between sm:p-6">
            <div class="min-w-0">
                <p class="text-xs font-semibold uppercase tracking-wider text-rose-800/80">Perhitungan</p>
                <h2 class="mt-1 text-xl font-bold text-slate-900 sm:text-2xl">Economic Order Quantity (EOQ)</h2>
                <p class="mt-1 text-sm text-slate-500">Tentukan jumlah pembelian onderdil paling optimal untuk meminimalkan biaya persediaan.</p>
            </div>
            <a
                href="{{ route('admin.perhitungan.eoq.create') }}"
                class="inline-flex shrink-0 items-center justify-center rounded-xl bg-gradient-to-r from-rose-900 to-rose-800 px-4 py-2.5 text-sm font-semibold text-white shadow-md shadow-rose-900/20 transition hover:from-rose-800 hover:to-rose-700"
            >
                + Tambah Perhitungan EOQ
            </a>
        </div>

        <div class="flex justify-center">
            <div class="admin-formula-card w-full max-w-xl p-4 sm:p-5">
                <p class="text-center text-[11px] font-semibold uppercase tracking-[0.18em] text-rose-700">Rumus EOQ</p>
                <p class="mt-1 text-center text-sm font-semibold text-rose-900 sm:text-base">EOQ = √((2 × D × S) / H)</p>
            </div>
        </div>

        <div class="admin-table-card">
            <div class="admin-table-toolbar">
                <form method="GET" action="{{ route('admin.perhitungan.eoq') }}" class="flex flex-col gap-3 sm:flex-row">
                    <input
                        type="search"
                        name="search"
                        value="{{ $search }}"
                        placeholder="Cari onderdil, D, S, H, hasil EOQ, atau keterangan..."
                        class="w-full flex-1 rounded-xl border border-slate-300 px-4 py-2.5 text-sm outline-none transition focus:border-rose-500 focus:ring-4 focus:ring-rose-100"
                    >
                    <button
                        type="submit"
                        class="inline-flex items-center justify-center rounded-xl border border-rose-200 bg-rose-50 px-4 py-2.5 text-sm font-semibold text-rose-900 transition hover:bg-rose-100"
                    >
                        Cari
                    </button>
                    @if ($search !== '')
                        <a
                            href="{{ route('admin.perhitungan.eoq') }}"
                            class="inline-flex items-center justify-center rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm font-medium text-slate-700 transition hover:bg-slate-50"
                        >
                            Reset
                        </a>
                    @endif
                </form>
            </div>

            @php
                $thClass = 'whitespace-nowrap px-3 py-3 text-center align-middle text-xs font-semibold uppercase tracking-wide text-slate-600';
                $tdClass = 'whitespace-nowrap px-3 py-3 text-center align-middle text-sm text-slate-700';
                $tdLabelClass = 'whitespace-nowrap px-3 py-3 text-center align-middle text-sm font-medium text-slate-900';
            @endphp

            <div class="calc-table-scroll">
                <table class="calc-table w-full text-sm">
                    <thead class="relative z-20">
                        <tr>
                            <th class="{{ $thClass }} text-left">Onderdil</th>
                            <x-calculation-table-header
                                label="D (Tahunan)"
                                info="Total kebutuhan onderdil selama 1 tahun."
                            />
                            <x-calculation-table-header
                                label="S (Pemesanan)"
                                info="Biaya setiap kali melakukan pemesanan."
                            />
                            <x-calculation-table-header
                                label="H (Penyimpanan)"
                                info="Biaya simpan per unit per tahun."
                            />
                            <x-calculation-table-header
                                label="Hasil EOQ"
                                info="Jumlah pembelian paling optimal per sekali pesan."
                            />
                            <th class="{{ $thClass }}">Keterangan</th>
                            <th class="{{ $thClass }}">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white">
                        @forelse ($eoqs as $eoq)
                            <tr class="transition hover:bg-rose-50/30">
                                <td class="{{ $tdLabelClass }} text-left">
                                    <span class="cell-truncate" title="{{ $eoq->onderdil?->kode_onderdil }} — {{ $eoq->onderdil?->nama_onderdil }}">
                                        {{ $eoq->onderdil?->kode_onderdil }} — {{ $eoq->onderdil?->nama_onderdil }}
                                    </span>
                                </td>
                                <td class="{{ $tdLabelClass }}">{{ number_format($eoq->kebutuhan_tahunan, 0, ',', '.') }}</td>
                                <td class="{{ $tdClass }}">Rp {{ number_format($eoq->biaya_pemesanan, 0, ',', '.') }}</td>
                                <td class="{{ $tdClass }}">Rp {{ number_format($eoq->biaya_penyimpanan, 0, ',', '.') }}</td>
                                <td class="{{ $tdLabelClass }}">
                                    <span class="inline-flex items-center rounded-full bg-rose-50 px-3 py-1 text-xs font-semibold text-rose-900 ring-1 ring-rose-100">
                                        {{ number_format($eoq->hasil_eoq, 0, ',', '.') }} unit
                                    </span>
                                </td>
                                <td class="{{ $tdClass }} text-left">
                                    <span class="cell-truncate" title="{{ $eoq->keterangan }}">
                                        {{ $eoq->keterangan ?: '-' }}
                                    </span>
                                </td>
                                <td class="{{ $tdClass }}">
                                    <div class="inline-flex items-center justify-center gap-2">
                                        <a
                                            href="{{ route('admin.perhitungan.eoq.edit', $eoq) }}"
                                            class="rounded-lg border border-slate-200 px-2.5 py-1.5 text-xs font-medium text-slate-700 transition hover:border-rose-200 hover:bg-rose-50 hover:text-rose-900"
                                        >
                                            Edit
                                        </a>
                                        <form
                                            id="delete-eoq-{{ $eoq->id }}"
                                            action="{{ route('admin.perhitungan.eoq.destroy', $eoq) }}"
                                            method="POST"
                                            class="hidden"
                                        >
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                        <button
                                            type="button"
                                            data-delete-trigger
                                            data-delete-form="delete-eoq-{{ $eoq->id }}"
                                            data-delete-title="Hapus Perhitungan EOQ?"
                                            data-delete-message="Data perhitungan EOQ berikut akan dihapus permanen."
                                            data-delete-item="{{ $eoq->onderdil?->kode_onderdil }} — EOQ {{ $eoq->hasil_eoq }} unit"
                                            class="rounded-lg border border-red-200 px-2.5 py-1.5 text-xs font-medium text-red-700 transition hover:bg-red-50"
                                        >
                                            Hapus
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-4 py-12 text-center text-slate-500">
                                    @if ($search !== '')
                                        Tidak ada data perhitungan EOQ yang cocok dengan pencarian.
                                    @else
                                        Belum ada data perhitungan EOQ. Klik <strong>Tambah Perhitungan EOQ</strong> untuk memulai.
                                    @endif
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($eoqs->hasPages())
                <div class="admin-table-footer">
                    {{ $eoqs->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
