@props([
    'onderdil' => null,
    'suppliers',
])

@php
    $isEdit = $onderdil !== null;
@endphp

<div class="grid grid-cols-1 gap-5 md:grid-cols-2">
    <div>
        <label for="kode_onderdil" class="mb-1.5 block text-sm font-medium text-slate-700">Kode Onderdil <span class="text-rose-600">*</span></label>
        <input
            type="text"
            id="kode_onderdil"
            name="kode_onderdil"
            value="{{ old('kode_onderdil', $onderdil?->kode_onderdil) }}"
            class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm shadow-sm outline-none transition focus:border-rose-500 focus:ring-4 focus:ring-rose-100"
            placeholder="Contoh: OND-001"
            required
        >
        @error('kode_onderdil')
            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="nama_onderdil" class="mb-1.5 block text-sm font-medium text-slate-700">Nama Onderdil <span class="text-rose-600">*</span></label>
        <input
            type="text"
            id="nama_onderdil"
            name="nama_onderdil"
            value="{{ old('nama_onderdil', $onderdil?->nama_onderdil) }}"
            class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm shadow-sm outline-none transition focus:border-rose-500 focus:ring-4 focus:ring-rose-100"
            placeholder="Nama sparepart"
            required
        >
        @error('nama_onderdil')
            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="jenis" class="mb-1.5 block text-sm font-medium text-slate-700">Jenis <span class="text-rose-600">*</span></label>
        <input
            type="text"
            id="jenis"
            name="jenis"
            value="{{ old('jenis', $onderdil?->jenis) }}"
            class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm shadow-sm outline-none transition focus:border-rose-500 focus:ring-4 focus:ring-rose-100"
            placeholder="Contoh: Busi, Filter Oli"
            required
        >
        @error('jenis')
            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="supplier_id" class="mb-1.5 block text-sm font-medium text-slate-700">Supplier <span class="text-rose-600">*</span></label>
        <x-shared.form-select id="supplier_id" name="supplier_id" required>
            <option value="">Pilih supplier</option>
            @foreach ($suppliers as $supplier)
                <option value="{{ $supplier->id }}" @selected((string) old('supplier_id', $onderdil?->supplier_id) === (string) $supplier->id)>
                    {{ $supplier->nama_supplier }}
                </option>
            @endforeach
        </x-shared.form-select>
        @error('supplier_id')
            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="harga" class="mb-1.5 block text-sm font-medium text-slate-700">Harga (Rp) <span class="text-rose-600">*</span></label>
        <input
            type="number"
            id="harga"
            name="harga"
            min="0"
            value="{{ old('harga', $onderdil?->harga) }}"
            class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm shadow-sm outline-none transition focus:border-rose-500 focus:ring-4 focus:ring-rose-100"
            placeholder="0"
            required
        >
        @error('harga')
            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="stok" class="mb-1.5 block text-sm font-medium text-slate-700">Stok <span class="text-rose-600">*</span></label>
        <input
            type="number"
            id="stok"
            name="stok"
            min="0"
            value="{{ old('stok', $onderdil?->stok ?? 0) }}"
            class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm shadow-sm outline-none transition focus:border-rose-500 focus:ring-4 focus:ring-rose-100"
            required
        >
        @error('stok')
            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="stok_minimum" class="mb-1.5 block text-sm font-medium text-slate-700">Batas Stok Minimum <span class="text-rose-600">*</span></label>
        <input
            type="number"
            id="stok_minimum"
            name="stok_minimum"
            min="1"
            value="{{ old('stok_minimum', $onderdil?->stok_minimum ?? 5) }}"
            class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm shadow-sm outline-none transition focus:border-rose-500 focus:ring-4 focus:ring-rose-100"
            required
        >
        <p class="mt-1 text-xs text-slate-500">Digunakan untuk status Menipis (stok &le; batas minimum).</p>
        @error('stok_minimum')
            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div class="md:col-span-2">
        <label for="deskripsi" class="mb-1.5 block text-sm font-medium text-slate-700">Deskripsi</label>
        <textarea
            id="deskripsi"
            name="deskripsi"
            rows="3"
            class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm shadow-sm outline-none transition focus:border-rose-500 focus:ring-4 focus:ring-rose-100"
            placeholder="Keterangan tambahan (opsional)"
        >{{ old('deskripsi', $onderdil?->deskripsi) }}</textarea>
        @error('deskripsi')
            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
        @enderror
    </div>
</div>
