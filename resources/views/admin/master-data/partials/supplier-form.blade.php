@props([
    'supplier' => null,
])

<div class="space-y-5">
    <div>
        <label for="nama_supplier" class="mb-1.5 block text-sm font-medium text-slate-700">Nama Supplier <span class="text-rose-600">*</span></label>
        <input
            type="text"
            id="nama_supplier"
            name="nama_supplier"
            value="{{ old('nama_supplier', $supplier?->nama_supplier) }}"
            class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm shadow-sm outline-none transition focus:border-rose-500 focus:ring-4 focus:ring-rose-100"
            placeholder="Nama perusahaan / toko supplier"
            required
        >
        @error('nama_supplier')
            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="kontak" class="mb-1.5 block text-sm font-medium text-slate-700">Kontak</label>
        <input
            type="text"
            id="kontak"
            name="kontak"
            value="{{ old('kontak', $supplier?->kontak) }}"
            class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm shadow-sm outline-none transition focus:border-rose-500 focus:ring-4 focus:ring-rose-100"
            placeholder="No. telepon / WhatsApp"
        >
        @error('kontak')
            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="alamat" class="mb-1.5 block text-sm font-medium text-slate-700">Alamat</label>
        <textarea
            id="alamat"
            name="alamat"
            rows="3"
            class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm shadow-sm outline-none transition focus:border-rose-500 focus:ring-4 focus:ring-rose-100"
            placeholder="Alamat supplier (opsional)"
        >{{ old('alamat', $supplier?->alamat) }}</textarea>
        @error('alamat')
            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
        @enderror
    </div>
</div>
