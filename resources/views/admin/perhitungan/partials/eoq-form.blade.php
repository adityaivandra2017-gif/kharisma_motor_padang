@props([
    'eoq' => null,
    'onderdils',
])

<div class="grid grid-cols-1 gap-5 md:grid-cols-2">
    <div class="md:col-span-2">
        <label for="onderdil_id" class="mb-1.5 block text-sm font-medium text-slate-700">Onderdil <span class="text-rose-600">*</span></label>
        <x-shared.form-select id="onderdil_id" name="onderdil_id" required>
            <option value="">Pilih onderdil</option>
            @foreach ($onderdils as $onderdil)
                <option value="{{ $onderdil->id }}" @selected((string) old('onderdil_id', $eoq?->onderdil_id) === (string) $onderdil->id)>
                    {{ $onderdil->kode_onderdil }} — {{ $onderdil->nama_onderdil }}
                </option>
            @endforeach
        </x-shared.form-select>
        @error('onderdil_id')
            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="kebutuhan_tahunan" class="mb-1.5 block text-sm font-medium text-slate-700">Kebutuhan Tahunan (D) <span class="text-rose-600">*</span></label>
        <input
            type="number"
            id="kebutuhan_tahunan"
            name="kebutuhan_tahunan"
            min="1"
            value="{{ old('kebutuhan_tahunan', $eoq?->kebutuhan_tahunan) }}"
            class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm shadow-sm outline-none transition focus:border-rose-500 focus:ring-4 focus:ring-rose-100"
            placeholder="Contoh: 1200"
            required
        >
        @error('kebutuhan_tahunan')
            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="biaya_pemesanan" class="mb-1.5 block text-sm font-medium text-slate-700">Biaya Pemesanan (S) <span class="text-rose-600">*</span></label>
        <input
            type="number"
            id="biaya_pemesanan"
            name="biaya_pemesanan"
            min="1"
            value="{{ old('biaya_pemesanan', $eoq?->biaya_pemesanan) }}"
            class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm shadow-sm outline-none transition focus:border-rose-500 focus:ring-4 focus:ring-rose-100"
            placeholder="Contoh: 50000"
            required
        >
        @error('biaya_pemesanan')
            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="biaya_penyimpanan" class="mb-1.5 block text-sm font-medium text-slate-700">Biaya Penyimpanan (H) <span class="text-rose-600">*</span></label>
        <input
            type="number"
            id="biaya_penyimpanan"
            name="biaya_penyimpanan"
            min="1"
            value="{{ old('biaya_penyimpanan', $eoq?->biaya_penyimpanan) }}"
            class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm shadow-sm outline-none transition focus:border-rose-500 focus:ring-4 focus:ring-rose-100"
            placeholder="Contoh: 2500"
            required
        >
        @error('biaya_penyimpanan')
            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div class="md:col-span-2">
        <label for="keterangan" class="mb-1.5 block text-sm font-medium text-slate-700">Keterangan</label>
        <textarea
            id="keterangan"
            name="keterangan"
            rows="3"
            class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm shadow-sm outline-none transition focus:border-rose-500 focus:ring-4 focus:ring-rose-100"
            placeholder="Catatan tambahan (opsional)"
        >{{ old('keterangan', $eoq?->keterangan) }}</textarea>
        @error('keterangan')
            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
        @enderror
    </div>
</div>
