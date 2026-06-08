@extends('layouts.admin')

@section('page-title', 'Barang Masuk')

@section('content')
    <div class="space-y-6">
        @if (session('success'))
            <div class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">
                {{ $errors->first() }}
            </div>
        @endif

        <div class="admin-section-card flex flex-col gap-4 p-5 sm:flex-row sm:items-center sm:justify-between sm:p-6">
            <div class="min-w-0">
                <p class="text-xs font-semibold uppercase tracking-wider text-rose-800/80">Transaksi</p>
                <h2 class="mt-1 text-xl font-bold text-slate-900 sm:text-2xl">Data Barang Masuk</h2>
                <p class="mt-1 text-sm text-slate-500">Catat penambahan stok onderdil dari supplier secara otomatis dan akurat.</p>
            </div>
            <a
                href="{{ route('admin.transaksi.masuk.create') }}"
                class="inline-flex shrink-0 items-center justify-center rounded-xl bg-gradient-to-r from-rose-900 to-rose-800 px-4 py-2.5 text-sm font-semibold text-white shadow-md shadow-rose-900/20 transition hover:from-rose-800 hover:to-rose-700"
            >
                + Tambah Barang Masuk
            </a>
        </div>

        <div class="admin-table-card">
            <div class="admin-table-toolbar">
                <form method="GET" action="{{ route('admin.transaksi.masuk') }}" class="flex flex-col gap-3 sm:flex-row">
                    <input
                        type="search"
                        name="search"
                        value="{{ $search }}"
                        placeholder="Cari tanggal, onderdil, supplier, jumlah, atau keterangan..."
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
                            href="{{ route('admin.transaksi.masuk') }}"
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

            <div class="data-table-scroll">
                <table class="w-full text-sm">
                    <thead>
                        <tr>
                            <th class="{{ $thClass }}">Tanggal</th>
                            <th class="{{ $thClass }}">Onderdil</th>
                            <th class="{{ $thClass }}">Supplier</th>
                            <th class="{{ $thClass }}">Jumlah</th>
                            <th class="{{ $thClass }}">Keterangan</th>
                            <th class="{{ $thClass }}">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white">
                        @forelse ($barangMasuks as $masuk)
                            <tr class="transition hover:bg-rose-50/30">
                                <td class="{{ $tdLabelClass }} whitespace-nowrap">{{ $masuk->tanggal_masuk->format('d-m-Y') }}</td>
                                <td class="{{ $tdClass }} text-left">
                                    <span class="cell-truncate" title="{{ $masuk->onderdil?->kode_onderdil }} — {{ $masuk->onderdil?->nama_onderdil }}">
                                        {{ $masuk->onderdil?->kode_onderdil }} — {{ $masuk->onderdil?->nama_onderdil }}
                                    </span>
                                </td>
                                <td class="{{ $tdClass }} text-left">
                                    <span class="cell-truncate" title="{{ $masuk->supplier?->nama_supplier }}">
                                        {{ $masuk->supplier?->nama_supplier ?? '-' }}
                                    </span>
                                </td>
                                <td class="{{ $tdLabelClass }}">+{{ number_format($masuk->jumlah, 0, ',', '.') }}</td>
                                <td class="{{ $tdClass }} text-left">
                                    <span class="cell-truncate" title="{{ $masuk->keterangan }}">
                                        {{ $masuk->keterangan ?: '-' }}
                                    </span>
                                </td>
                                <td class="{{ $tdClass }}">
                                    <div class="inline-flex items-center justify-center gap-2">
                                        <a
                                            href="{{ route('admin.transaksi.masuk.edit', $masuk) }}"
                                            class="rounded-lg border border-slate-200 px-2.5 py-1.5 text-xs font-medium text-slate-700 transition hover:border-rose-200 hover:bg-rose-50 hover:text-rose-900"
                                        >
                                            Edit
                                        </a>
                                        <form
                                            id="delete-masuk-{{ $masuk->id }}"
                                            action="{{ route('admin.transaksi.masuk.destroy', $masuk) }}"
                                            method="POST"
                                            class="hidden"
                                        >
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                        <button
                                            type="button"
                                            data-delete-trigger
                                            data-delete-form="delete-masuk-{{ $masuk->id }}"
                                            data-delete-title="Hapus Data Barang Masuk?"
                                            data-delete-message="Data transaksi berikut akan dihapus dan stok onderdil akan dikurangi kembali."
                                            data-delete-item="{{ $masuk->tanggal_masuk->format('d-m-Y') }} — {{ $masuk->onderdil?->kode_onderdil }} ({{ $masuk->jumlah }})"
                                            class="rounded-lg border border-red-200 px-2.5 py-1.5 text-xs font-medium text-red-700 transition hover:bg-red-50"
                                        >
                                            Hapus
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-4 py-12 text-center text-slate-500">
                                    @if ($search !== '')
                                        Tidak ada data barang masuk yang cocok dengan pencarian.
                                    @else
                                        Belum ada transaksi barang masuk. Klik <strong>Tambah Barang Masuk</strong> untuk memulai.
                                    @endif
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($barangMasuks->hasPages())
                <div class="admin-table-footer">
                    {{ $barangMasuks->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
