@props([
    'rop' => null,
    'onderdils',
])

<div class="grid grid-cols-1 gap-5 md:grid-cols-2">
    <div class="md:col-span-2">
        <label for="onderdil_id" class="mb-1.5 block text-sm font-medium text-slate-700">Onderdil <span class="text-rose-600">*</span></label>
        <x-shared.form-select id="onderdil_id" name="onderdil_id" required>
            <option value="">Pilih onderdil</option>
            @foreach ($onderdils as $onderdil)
                <option value="{{ $onderdil->id }}" @selected((string) old('onderdil_id', $rop?->onderdil_id) === (string) $onderdil->id)>
                    {{ $onderdil->kode_onderdil }} — {{ $onderdil->nama_onderdil }} (Stok: {{ $onderdil->stok }})
                </option>
            @endforeach
        </x-shared.form-select>
        @error('onderdil_id')
            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="lead_time" class="mb-1.5 block text-sm font-medium text-slate-700">Lead Time (hari) <span class="text-rose-600">*</span></label>
        <input
            type="number"
            id="lead_time"
            name="lead_time"
            min="1"
            value="{{ old('lead_time', $rop?->lead_time) }}"
            class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm shadow-sm outline-none transition focus:border-rose-500 focus:ring-4 focus:ring-rose-100"
            placeholder="Contoh: 7"
            required
        >
        @error('lead_time')
            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="kebutuhan_per_hari" class="mb-1.5 block text-sm font-medium text-slate-700">Kebutuhan per Hari <span class="text-rose-600">*</span></label>
        <input
            type="number"
            id="kebutuhan_per_hari"
            name="kebutuhan_per_hari"
            min="1"
            value="{{ old('kebutuhan_per_hari', $rop?->kebutuhan_per_hari) }}"
            class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm shadow-sm outline-none transition focus:border-rose-500 focus:ring-4 focus:ring-rose-100"
            placeholder="Contoh: 5"
            required
        >
        @error('kebutuhan_per_hari')
            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="safety_stock" class="mb-1.5 block text-sm font-medium text-slate-700">Safety Stock <span class="text-rose-600">*</span></label>
        <input
            type="number"
            id="safety_stock"
            name="safety_stock"
            min="0"
            value="{{ old('safety_stock', $rop?->safety_stock) }}"
            class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm shadow-sm outline-none transition focus:border-rose-500 focus:ring-4 focus:ring-rose-100"
            placeholder="Contoh: 10"
            required
        >
        @error('safety_stock')
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
        >{{ old('keterangan', $rop?->keterangan) }}</textarea>
        @error('keterangan')
            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
        @enderror
    </div>
</div>
