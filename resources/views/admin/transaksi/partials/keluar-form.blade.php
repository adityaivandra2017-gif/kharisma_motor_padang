@props([
    'barangKeluar' => null,
    'onderdils',
])

<div class="grid grid-cols-1 gap-5 md:grid-cols-2">
    <div>
        <label for="tanggal_keluar" class="mb-1.5 block text-sm font-medium text-slate-700">Tanggal Keluar <span class="text-rose-600">*</span></label>
        <x-admin.date-picker
            id="tanggal_keluar"
            name="tanggal_keluar"
            :value="old('tanggal_keluar', $barangKeluar?->tanggal_keluar?->format('Y-m-d') ?? now()->format('Y-m-d'))"
            placeholder="dd/mm/yyyy"
            required
        />
        @error('tanggal_keluar')
            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="jumlah" class="mb-1.5 block text-sm font-medium text-slate-700">Jumlah Keluar <span class="text-rose-600">*</span></label>
        <input
            type="number"
            id="jumlah"
            name="jumlah"
            min="1"
            value="{{ old('jumlah', $barangKeluar?->jumlah) }}"
            class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm shadow-sm outline-none transition focus:border-rose-500 focus:ring-4 focus:ring-rose-100"
            placeholder="Contoh: 5"
            required
        >
        @error('jumlah')
            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div class="md:col-span-2">
        <label for="onderdil_id" class="mb-1.5 block text-sm font-medium text-slate-700">Onderdil <span class="text-rose-600">*</span></label>
        <x-shared.form-select id="onderdil_id" name="onderdil_id" required>
            <option value="">Pilih onderdil</option>
            @foreach ($onderdils as $onderdil)
                <option value="{{ $onderdil->id }}" @selected((string) old('onderdil_id', $barangKeluar?->onderdil_id) === (string) $onderdil->id)>
                    {{ $onderdil->kode_onderdil }} — {{ $onderdil->nama_onderdil }} (Stok: {{ $onderdil->stok }})
                </option>
            @endforeach
        </x-shared.form-select>
        @error('onderdil_id')
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
        >{{ old('keterangan', $barangKeluar?->keterangan) }}</textarea>
        @error('keterangan')
            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
        @enderror
    </div>
</div>
