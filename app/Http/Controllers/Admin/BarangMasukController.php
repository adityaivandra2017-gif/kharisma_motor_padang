<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\BarangMasukRequest;
use App\Models\BarangMasuk;
use App\Models\Onderdil;
use App\Models\Supplier;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class BarangMasukController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->string('search')->trim()->toString();

        $barangMasuks = BarangMasuk::query()
            ->with([
                'onderdil:id,kode_onderdil,nama_onderdil',
                'supplier:id,nama_supplier',
            ])
            ->when($search !== '', function ($query) use ($search): void {
                $query->where(function ($builder) use ($search): void {
                    $builder
                        ->where('keterangan', 'like', "%{$search}%")
                        ->orWhere('tanggal_masuk', 'like', "%{$search}%")
                        ->orWhere('jumlah', 'like', "%{$search}%")
                        ->orWhereHas('onderdil', function ($onderdil) use ($search): void {
                            $onderdil
                                ->where('kode_onderdil', 'like', "%{$search}%")
                                ->orWhere('nama_onderdil', 'like', "%{$search}%");
                        })
                        ->orWhereHas('supplier', fn ($supplier) => $supplier->where('nama_supplier', 'like', "%{$search}%"));
                });
            })
            ->orderByDesc('tanggal_masuk')
            ->orderByDesc('id')
            ->paginate(10)
            ->withQueryString();

        return view('admin.transaksi.masuk', compact('barangMasuks', 'search'));
    }

    public function create(): View
    {
        $onderdils = Onderdil::query()
            ->with('supplier:id,nama_supplier')
            ->orderBy('nama_onderdil')
            ->get(['id', 'kode_onderdil', 'nama_onderdil', 'supplier_id']);
        $suppliers = Supplier::orderBy('nama_supplier')->get(['id', 'nama_supplier']);

        return view('admin.transaksi.masuk-form-page', [
            'barangMasuk' => null,
            'onderdils' => $onderdils,
            'suppliers' => $suppliers,
        ]);
    }

    public function store(BarangMasukRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        DB::transaction(function () use ($validated): void {
            $onderdil = Onderdil::query()->lockForUpdate()->findOrFail($validated['onderdil_id']);
            $onderdil->increment('stok', $validated['jumlah']);

            BarangMasuk::create($validated);
        });

        return redirect()
            ->route('admin.transaksi.masuk')
            ->with('success', 'Data barang masuk berhasil ditambahkan dan stok diperbarui.');
    }

    public function edit(BarangMasuk $masuk): View
    {
        $onderdils = Onderdil::query()
            ->with('supplier:id,nama_supplier')
            ->orderBy('nama_onderdil')
            ->get(['id', 'kode_onderdil', 'nama_onderdil', 'supplier_id']);
        $suppliers = Supplier::orderBy('nama_supplier')->get(['id', 'nama_supplier']);

        return view('admin.transaksi.masuk-form-page', [
            'barangMasuk' => $masuk,
            'onderdils' => $onderdils,
            'suppliers' => $suppliers,
        ]);
    }

    public function update(BarangMasukRequest $request, BarangMasuk $masuk): RedirectResponse
    {
        $validated = $request->validated();

        DB::transaction(function () use ($masuk, $validated): void {
            $masuk->refresh();

            $oldOnderdilId = $masuk->onderdil_id;
            $newOnderdilId = (int) $validated['onderdil_id'];
            $oldJumlah = $masuk->jumlah;
            $newJumlah = (int) $validated['jumlah'];

            if ($oldOnderdilId === $newOnderdilId) {
                $onderdil = Onderdil::query()->lockForUpdate()->findOrFail($newOnderdilId);
                $delta = $newJumlah - $oldJumlah;
                $newStok = $onderdil->stok + $delta;

                if ($newStok < 0) {
                    throw ValidationException::withMessages([
                        'jumlah' => 'Perubahan jumlah tidak valid karena stok onderdil menjadi negatif.',
                    ]);
                }

                $onderdil->update(['stok' => $newStok]);
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

                $stokOldAfterRollback = $oldOnderdil->stok - $oldJumlah;
                if ($stokOldAfterRollback < 0) {
                    throw ValidationException::withMessages([
                        'jumlah' => 'Perubahan tidak dapat diproses karena stok onderdil lama tidak mencukupi.',
                    ]);
                }

                $oldOnderdil->update(['stok' => $stokOldAfterRollback]);
                $newOnderdil->increment('stok', $newJumlah);
            }

            $masuk->update($validated);
        });

        return redirect()
            ->route('admin.transaksi.masuk')
            ->with('success', 'Data barang masuk berhasil diperbarui dan stok disesuaikan.');
    }

    public function destroy(BarangMasuk $masuk): RedirectResponse
    {
        DB::transaction(function () use ($masuk): void {
            $masuk->refresh();
            $onderdil = Onderdil::query()->lockForUpdate()->findOrFail($masuk->onderdil_id);

            $newStok = $onderdil->stok - $masuk->jumlah;
            if ($newStok < 0) {
                throw ValidationException::withMessages([
                    'stok' => 'Data barang masuk tidak dapat dihapus karena stok onderdil saat ini tidak mencukupi.',
                ]);
            }

            $onderdil->update(['stok' => $newStok]);
            $masuk->delete();
        });

        return redirect()
            ->route('admin.transaksi.masuk')
            ->with('success', 'Data barang masuk berhasil dihapus dan stok dikembalikan.');
    }
}
