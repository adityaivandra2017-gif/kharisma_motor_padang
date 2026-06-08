@extends('layouts.admin')

@php
    $isEdit = $eoq !== null;
@endphp

@section('page-title', 'Economic Order Quantity (EOQ)')

@section('content')
    <div class="mx-auto max-w-4xl space-y-6">
        <div class="flex items-start gap-3">
            @include('partials.shared.back-button', [
                'href' => route('admin.perhitungan.eoq'),
                'label' => 'Kembali ke Data EOQ',
            ])
            <div class="min-w-0">
                <h2 class="text-xl font-bold text-slate-900 sm:text-2xl">
                    {{ $isEdit ? 'Edit Perhitungan EOQ' : 'Tambah Perhitungan EOQ' }}
                </h2>
                @if ($isEdit)
                    <p class="mt-1 text-sm text-slate-500">{{ $eoq->onderdil?->kode_onderdil }} — {{ $eoq->onderdil?->nama_onderdil }}</p>
                @endif
            </div>
        </div>

        <form method="POST" action="{{ $isEdit ? route('admin.perhitungan.eoq.update', $eoq) : route('admin.perhitungan.eoq.store') }}" class="admin-form-card p-5 sm:p-8">
            @csrf
            @if ($isEdit)
                @method('PUT')
            @endif

            @include('admin.perhitungan.partials.eoq-form', [
                'eoq' => $eoq,
                'onderdils' => $onderdils,
            ])

            <div class="mt-6 flex flex-col-reverse gap-3 border-t border-slate-100 pt-6 sm:flex-row sm:justify-end">
                <a
                    href="{{ route('admin.perhitungan.eoq') }}"
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
