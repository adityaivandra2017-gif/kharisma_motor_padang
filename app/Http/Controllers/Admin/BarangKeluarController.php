<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\BarangKeluarRequest;
use App\Models\BarangKeluar;
use App\Models\Onderdil;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class BarangKeluarController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->string('search')->trim()->toString();

        $barangKeluars = BarangKeluar::query()
            ->with('onderdil:id,kode_onderdil,nama_onderdil')
            ->when($search !== '', function ($query) use ($search): void {
                $query->where(function ($builder) use ($search): void {
                    $builder
                        ->where('keterangan', 'like', "%{$search}%")
                        ->orWhere('tanggal_keluar', 'like', "%{$search}%")
                        ->orWhere('jumlah', 'like', "%{$search}%")
                        ->orWhereHas('onderdil', function ($onderdil) use ($search): void {
                            $onderdil
                                ->where('kode_onderdil', 'like', "%{$search}%")
                                ->orWhere('nama_onderdil', 'like', "%{$search}%");
                        });
                });
            })
            ->orderByDesc('tanggal_keluar')
            ->orderByDesc('id')
            ->paginate(10)
            ->withQueryString();

        return view('admin.transaksi.keluar', compact('barangKeluars', 'search'));
    }

    public function create(): View
    {
        $onderdils = Onderdil::query()
            ->orderBy('nama_onderdil')
            ->get(['id', 'kode_onderdil', 'nama_onderdil', 'stok']);

        return view('admin.transaksi.keluar-form-page', [
            'barangKeluar' => null,
            'onderdils' => $onderdils,
        ]);
    }

    public function store(BarangKeluarRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        DB::transaction(function () use ($validated): void {
            $onderdil = Onderdil::query()->lockForUpdate()->findOrFail($validated['onderdil_id']);

            if ($onderdil->stok < $validated['jumlah']) {
                throw ValidationException::withMessages([
                    'jumlah' => 'Stok tidak mencukupi. Stok tersedia saat ini: '.$onderdil->stok.'.',
                ]);
            }

            $onderdil->decrement('stok', $validated['jumlah']);
            BarangKeluar::create($validated);
        });

        return redirect()
            ->route('admin.transaksi.keluar')
            ->with('success', 'Data barang keluar berhasil ditambahkan dan stok diperbarui.');
    }

    public function edit(BarangKeluar $keluar): View
    {
        $onderdils = Onderdil::query()
            ->orderBy('nama_onderdil')
            ->get(['id', 'kode_onderdil', 'nama_onderdil', 'stok']);

        return view('admin.transaksi.keluar-form-page', [
            'barangKeluar' => $keluar,
            'onderdils' => $onderdils,
        ]);
    }

    public function update(BarangKeluarRequest $request, BarangKeluar $keluar): RedirectResponse
    {
        $validated = $request->validated();

        DB::transaction(function () use ($keluar, $validated): void {
            $keluar->refresh();

            $oldOnderdilId = $keluar->onderdil_id;
            $newOnderdilId = (int) $validated['onderdil_id'];
            $oldJumlah = $keluar->jumlah;
            $newJumlah = (int) $validated['jumlah'];

            if ($oldOnderdilId === $newOnderdilId) {
                $onderdil = Onderdil::query()->lockForUpdate()->findOrFail($newOnderdilId);
                $availableStock = $onderdil->stok + $oldJumlah;

                if ($newJumlah > $availableStock) {
                    throw ValidationException::withMessages([
                        'jumlah' => 'Jumlah keluar melebihi stok tersedia. Stok maksimum yang bisa diinput: '.$availableStock.'.',
                    ]);
                }

                $onderdil->update(['stok' => $availableStock - $newJumlah]);
            } else {
                $orderedIds = [$oldOnderdilId, $newOnderdilId];
                sort($orderedIds);

                $lockedOnderdils = Onderdil::query()
                    ->whereIn('id', $orderedIds)
                    ->lockForUpdate()
                    ->get()
                    ->keyBy('id');

                $oldOnderdil = $lockedOnderdils->get($oldOnderdilId);
                $newOnderdil = $lockedOnderdils->get($newOnderdilId);

                if (! $oldOnderdil || ! $newOnderdil) {
                    throw ValidationException::withMessages([
                        'onderdil_id' => 'Data onderdil tidak ditemukan.',
                    ]);
                }

                $newOnderdilStockAfter = $newOnderdil->stok - $newJumlah;
                if ($newOnderdilStockAfter < 0) {
                    throw ValidationException::withMessages([
                        'jumlah' => 'Stok onderdil baru tidak mencukupi untuk jumlah keluar tersebut.',
                    ]);
                }

                $oldOnderdil->increment('stok', $oldJumlah);
                $newOnderdil->update(['stok' => $newOnderdilStockAfter]);
            }

            $keluar->update($validated);
        });

        return redirect()
            ->route('admin.transaksi.keluar')
            ->with('success', 'Data barang keluar berhasil diperbarui dan stok disesuaikan.');
    }

    public function destroy(BarangKeluar $keluar): RedirectResponse
    {
        DB::transaction(function () use ($keluar): void {
            $keluar->refresh();
            $onderdil = Onderdil::query()->lockForUpdate()->findOrFail($keluar->onderdil_id);

            $onderdil->increment('stok', $keluar->jumlah);
            $keluar->delete();
        });

        return redirect()
            ->route('admin.transaksi.keluar')
            ->with('success', 'Data barang keluar berhasil dihapus dan stok dikembalikan.');
    }
}
