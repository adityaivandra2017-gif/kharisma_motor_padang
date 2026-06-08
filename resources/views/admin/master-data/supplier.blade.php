@extends('layouts.admin')

@section('page-title', 'Data Supplier')

@section('content')
    <div class="space-y-6">
        @if (session('success'))
            <div class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">
                {{ session('error') }}
            </div>
        @endif

        <div class="admin-section-card flex flex-col gap-4 p-5 sm:flex-row sm:items-center sm:justify-between sm:p-6">
            <div>
                <p class="text-xs font-semibold uppercase tracking-wider text-rose-800/80">Master Data</p>
                <h2 class="mt-1 text-xl font-bold text-slate-900 sm:text-2xl">Data Supplier</h2>
                <p class="mt-1 text-sm text-slate-500">Kelola pemasok onderdil yang terhubung dengan data inventory.</p>
            </div>
            <a
                href="{{ route('admin.master-data.supplier.create') }}"
                class="inline-flex shrink-0 items-center justify-center rounded-xl bg-gradient-to-r from-rose-900 to-rose-800 px-4 py-2.5 text-sm font-semibold text-white shadow-md shadow-rose-900/20 transition hover:from-rose-800 hover:to-rose-700"
            >
                + Tambah Supplier
            </a>
        </div>

        <div class="admin-table-card">
            <div class="admin-table-toolbar">
                <form method="GET" action="{{ route('admin.master-data.supplier') }}" class="flex flex-col gap-3 sm:flex-row">
                    <input
                        type="search"
                        name="search"
                        value="{{ $search }}"
                        placeholder="Cari nama, kontak, atau alamat..."
                        class="w-full flex-1 rounded-xl border border-slate-300 py-2.5 px-4 text-sm outline-none transition focus:border-rose-500 focus:ring-4 focus:ring-rose-100"
                    >
                    <button
                        type="submit"
                        class="inline-flex items-center justify-center rounded-xl border border-rose-200 bg-rose-50 px-4 py-2.5 text-sm font-semibold text-rose-900 transition hover:bg-rose-100"
                    >
                        Cari
                    </button>
                    @if ($search !== '')
                        <a
                            href="{{ route('admin.master-data.supplier') }}"
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
                            <th class="{{ $thClass }}">Nama Supplier</th>
                            <th class="{{ $thClass }}">Kontak</th>
                            <th class="{{ $thClass }}">Alamat</th>
                            <th class="{{ $thClass }}">Onderdil</th>
                            <th class="{{ $thClass }}">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white">
                        @forelse ($suppliers as $supplier)
                            <tr class="transition hover:bg-rose-50/30">
                                <td class="{{ $tdLabelClass }} text-left">
                                    <span class="cell-truncate" title="{{ $supplier->nama_supplier }}">{{ $supplier->nama_supplier }}</span>
                                </td>
                                <td class="{{ $tdClass }} text-left">
                                    <span class="cell-truncate" title="{{ $supplier->kontak }}">{{ $supplier->kontak ?? '-' }}</span>
                                </td>
                                <td class="{{ $tdClass }} text-left">
                                    <span class="cell-truncate" title="{{ $supplier->alamat }}">{{ $supplier->alamat ?? '-' }}</span>
                                </td>
                                <td class="{{ $tdClass }}">
                                    <span class="inline-flex min-w-[2rem] items-center justify-center rounded-full bg-rose-50 px-2.5 py-1 text-xs font-semibold text-rose-900 ring-1 ring-rose-100">
                                        {{ $supplier->onderdils_count }}
                                    </span>
                                </td>
                                <td class="{{ $tdClass }}">
                                    <div class="inline-flex items-center justify-center gap-2">
                                        <a
                                            href="{{ route('admin.master-data.supplier.edit', $supplier) }}"
                                            class="rounded-lg border border-slate-200 px-2.5 py-1.5 text-xs font-medium text-slate-700 transition hover:border-rose-200 hover:bg-rose-50 hover:text-rose-900"
                                        >
                                            Edit
                                        </a>
                                        <form
                                            id="delete-supplier-{{ $supplier->id }}"
                                            action="{{ route('admin.master-data.supplier.destroy', $supplier) }}"
                                            method="POST"
                                            class="hidden"
                                        >
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                        <button
                                            type="button"
                                            data-delete-trigger
                                            data-delete-form="delete-supplier-{{ $supplier->id }}"
                                            data-delete-title="Hapus Data Supplier?"
                                            data-delete-message="Data supplier berikut akan dihapus permanen dari sistem."
                                            data-delete-item="{{ $supplier->nama_supplier }}"
                                            data-delete-subtitle="{{ $supplier->onderdils_count > 0 ? 'Supplier masih dipakai oleh ' . $supplier->onderdils_count . ' onderdil — penghapusan akan ditolak sistem.' : 'Tindakan ini tidak dapat dibatalkan.' }}"
                                            class="rounded-lg border border-red-200 px-2.5 py-1.5 text-xs font-medium text-red-700 transition hover:bg-red-50"
                                        >
                                            Hapus
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-12 text-center text-slate-500">
                                    @if ($search !== '')
                                        Tidak ada data supplier yang cocok dengan pencarian.
                                    @else
                                        Belum ada data supplier. Klik <strong>Tambah Supplier</strong> untuk memulai.
                                    @endif
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($suppliers->hasPages())
                <div class="admin-table-footer">
                    {{ $suppliers->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
