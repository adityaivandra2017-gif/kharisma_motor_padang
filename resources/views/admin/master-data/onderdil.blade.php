@extends('layouts.admin')

@section('page-title', 'Data Onderdil')

@section('content')
    <div class="space-y-6">
        @if (session('success'))
            <div class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">
                {{ session('success') }}
            </div>
        @endif

        <div class="admin-section-card flex flex-col gap-4 p-5 sm:flex-row sm:items-center sm:justify-between sm:p-6">
            <div>
                <p class="text-xs font-semibold uppercase tracking-wider text-rose-800/80">Master Data</p>
                <h2 class="mt-1 text-xl font-bold text-slate-900 sm:text-2xl">Data Onderdil</h2>
                <p class="mt-1 text-sm text-slate-500">Kelola data sparepart untuk proses inventory dan perhitungan EOQ & ROP.</p>
            </div>
            <a
                href="{{ route('admin.master-data.onderdil.create') }}"
                class="inline-flex shrink-0 items-center justify-center rounded-xl bg-gradient-to-r from-rose-900 to-rose-800 px-4 py-2.5 text-sm font-semibold text-white shadow-md shadow-rose-900/20 transition hover:from-rose-800 hover:to-rose-700"
            >
                + Tambah Onderdil
            </a>
        </div>

        <div class="admin-table-card">
            <div class="admin-table-toolbar">
                <form method="GET" action="{{ route('admin.master-data.onderdil') }}" class="flex flex-col gap-3 sm:flex-row">
                    <div class="relative flex-1">
                        <input
                            type="search"
                            name="search"
                            value="{{ $search }}"
                            placeholder="Cari kode, nama, jenis, atau supplier..."
                            class="w-full rounded-xl border border-slate-300 py-2.5 pl-4 pr-4 text-sm outline-none transition focus:border-rose-500 focus:ring-4 focus:ring-rose-100"
                        >
                    </div>
                    <button
                        type="submit"
                        class="inline-flex items-center justify-center rounded-xl border border-rose-200 bg-rose-50 px-4 py-2.5 text-sm font-semibold text-rose-900 transition hover:bg-rose-100"
                    >
                        Cari
                    </button>
                    @if ($search !== '')
                        <a
                            href="{{ route('admin.master-data.onderdil') }}"
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
                            <th class="{{ $thClass }}">Kode</th>
                            <th class="{{ $thClass }}">Nama</th>
                            <th class="{{ $thClass }}">Jenis</th>
                            <th class="{{ $thClass }}">Supplier</th>
                            <th class="{{ $thClass }}">Harga</th>
                            <th class="{{ $thClass }}">Stok</th>
                            <th class="{{ $thClass }}">Status</th>
                            <th class="{{ $thClass }}">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white">
                        @forelse ($onderdils as $onderdil)
                            <tr class="transition hover:bg-rose-50/30">
                                <td class="{{ $tdLabelClass }} whitespace-nowrap">{{ $onderdil->kode_onderdil }}</td>
                                <td class="{{ $tdClass }} text-left">
                                    <span class="cell-truncate" title="{{ $onderdil->nama_onderdil }}">{{ $onderdil->nama_onderdil }}</span>
                                </td>
                                <td class="{{ $tdClass }} text-left">
                                    <span class="cell-truncate" title="{{ $onderdil->jenis }}">{{ $onderdil->jenis }}</span>
                                </td>
                                <td class="{{ $tdClass }} text-left">
                                    <span class="cell-truncate" title="{{ $onderdil->supplier?->nama_supplier }}">{{ $onderdil->supplier?->nama_supplier ?? '-' }}</span>
                                </td>
                                <td class="{{ $tdClass }} whitespace-nowrap">Rp {{ number_format($onderdil->harga, 0, ',', '.') }}</td>
                                <td class="{{ $tdLabelClass }}">{{ $onderdil->stok }}</td>
                                <td class="{{ $tdClass }}">
                                    <div class="flex justify-center">
                                        @include('admin.master-data.partials.status-stok-badge', ['status' => $onderdil->status_stok])
                                    </div>
                                </td>
                                <td class="{{ $tdClass }}">
                                    <div class="inline-flex items-center justify-center gap-2">
                                        <a
                                            href="{{ route('admin.master-data.onderdil.edit', $onderdil) }}"
                                            class="rounded-lg border border-slate-200 px-2.5 py-1.5 text-xs font-medium text-slate-700 transition hover:border-rose-200 hover:bg-rose-50 hover:text-rose-900"
                                        >
                                            Edit
                                        </a>
                                        <form
                                            id="delete-onderdil-{{ $onderdil->id }}"
                                            action="{{ route('admin.master-data.onderdil.destroy', $onderdil) }}"
                                            method="POST"
                                            class="hidden"
                                        >
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                        <button
                                            type="button"
                                            data-delete-trigger
                                            data-delete-form="delete-onderdil-{{ $onderdil->id }}"
                                            data-delete-title="Hapus Data Onderdil?"
                                            data-delete-message="Data onderdil berikut akan dihapus permanen dari sistem."
                                            data-delete-item="{{ $onderdil->kode_onderdil }} — {{ $onderdil->nama_onderdil }}"
                                            class="rounded-lg border border-red-200 px-2.5 py-1.5 text-xs font-medium text-red-700 transition hover:bg-red-50"
                                        >
                                            Hapus
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-4 py-12 text-center text-slate-500">
                                    @if ($search !== '')
                                        Tidak ada data onderdil yang cocok dengan pencarian.
                                    @else
                                        Belum ada data onderdil. Klik <strong>Tambah Onderdil</strong> untuk memulai.
                                    @endif
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($onderdils->hasPages())
                <div class="admin-table-footer">
                    {{ $onderdils->links() }}
                </div>
            @endif
        </div>
    </div>

@endsection
