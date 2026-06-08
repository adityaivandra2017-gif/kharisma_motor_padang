@extends('layouts.admin')

@php
    $isEdit = $rop !== null;
@endphp

@section('page-title', 'Reorder Point (ROP)')

@section('content')
    <div class="mx-auto max-w-4xl space-y-6">
        <div class="flex items-start gap-3">
            @include('partials.shared.back-button', [
                'href' => route('admin.perhitungan.rop'),
                'label' => 'Kembali ke Data ROP',
            ])
            <div class="min-w-0">
                <h2 class="text-xl font-bold text-slate-900 sm:text-2xl">
                    {{ $isEdit ? 'Edit Perhitungan ROP' : 'Tambah Perhitungan ROP' }}
                </h2>
                @if ($isEdit)
                    <p class="mt-1 text-sm text-slate-500">{{ $rop->onderdil?->kode_onderdil }} — {{ $rop->onderdil?->nama_onderdil }}</p>
                @endif
            </div>
        </div>

        <form method="POST" action="{{ $isEdit ? route('admin.perhitungan.rop.update', $rop) : route('admin.perhitungan.rop.store') }}" class="admin-form-card p-5 sm:p-8">
            @csrf
            @if ($isEdit)
                @method('PUT')
            @endif

            @include('admin.perhitungan.partials.rop-form', [
                'rop' => $rop,
                'onderdils' => $onderdils,
            ])

            <div class="mt-6 flex flex-col-reverse gap-3 border-t border-slate-100 pt-6 sm:flex-row sm:justify-end">
                <a
                    href="{{ route('admin.perhitungan.rop') }}"
                    class="inline-flex items-center justify-center rounded-xl border border-slate-300 px-4 py-2.5 text-sm font-medium text-slate-700 transition hover:bg-slate-50"
                >
                    Batal
                </a>
                <button
                    type="submit"
                    class="inline-flex items-center justify-center rounded-xl bg-gradient-to-r from-rose-900 to-rose-800 px-4 py-2.5 text-sm font-semibold text-white shadow-md shadow-rose-900/20 transition hover:from-rose-800 hover:to-rose-700"
                >
                    {{ $isEdit ? 'Perbarui Perhitungan' : 'Simpan Perhitungan' }}
                </button>
            </div>
        </form>
    </div>
@endsection
