<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\RopRequest;
use App\Models\Onderdil;
use App\Models\Rop;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RopController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->string('search')->trim()->toString();

        $rops = Rop::query()
            ->with('onderdil:id,kode_onderdil,nama_onderdil,stok')
            ->when($search !== '', function ($query) use ($search): void {
                $query->where(function ($builder) use ($search): void {
                    $builder
                        ->where('lead_time', 'like', "%{$search}%")
                        ->orWhere('kebutuhan_per_hari', 'like', "%{$search}%")
                        ->orWhere('safety_stock', 'like', "%{$search}%")
                        ->orWhere('hasil_rop', 'like', "%{$search}%")
                        ->orWhere('status_stok', 'like', "%{$search}%")
                        ->orWhere('keterangan', 'like', "%{$search}%")
                        ->orWhereHas('onderdil', function ($onderdil) use ($search): void {
                            $onderdil
                                ->where('kode_onderdil', 'like', "%{$search}%")
                                ->orWhere('nama_onderdil', 'like', "%{$search}%");
                        });
                });
            })
            ->orderByDesc('id')
            ->paginate(10)
            ->withQueryString();

        $restockSoonCount = Rop::query()
            ->whereHas('onderdil', function ($query): void {
                $query->whereColumn('onderdil.stok', '<=', 'rop.hasil_rop');
            })
            ->count();

        return view('admin.perhitungan.rop', compact('rops', 'search', 'restockSoonCount'));
    }

    public function create(): View
    {
        $onderdils = Onderdil::orderBy('nama_onderdil')->get(['id', 'kode_onderdil', 'nama_onderdil', 'stok']);

        return view('admin.perhitungan.rop-form-page', [
            'rop' => null,
            'onderdils' => $onderdils,
        ]);
    }

    public function store(RopRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $onderdil = Onderdil::findOrFail($validated['onderdil_id']);
        $validated['hasil_rop'] = $this->calculateRop(
            $validated['lead_time'],
            $validated['kebutuhan_per_hari'],
            $validated['safety_stock']
        );
        $validated['status_stok'] = Rop::resolveLiveStockStatus($onderdil->stok, $validated['hasil_rop'], $validated['safety_stock']);

        Rop::create($validated);

        return redirect()
            ->route('admin.perhitungan.rop')
            ->with('success', 'Perhitungan ROP berhasil ditambahkan.');
    }

    public function edit(Rop $rop): View
    {
        $onderdils = Onderdil::orderBy('nama_onderdil')->get(['id', 'kode_onderdil', 'nama_onderdil', 'stok']);

        return view('admin.perhitungan.rop-form-page', compact('rop', 'onderdils'));
    }

    public function update(RopRequest $request, Rop $rop): RedirectResponse
    {
        $validated = $request->validated();

        $onderdil = Onderdil::findOrFail($validated['onderdil_id']);
        $validated['hasil_rop'] = $this->calculateRop(
            $validated['lead_time'],
            $validated['kebutuhan_per_hari'],
            $validated['safety_stock']
        );
        $validated['status_stok'] = Rop::resolveLiveStockStatus($onderdil->stok, $validated['hasil_rop'], $validated['safety_stock']);

        $rop->update($validated);

        return redirect()
            ->route('admin.perhitungan.rop')
            ->with('success', 'Perhitungan ROP berhasil diperbarui.');
    }

    public function destroy(Rop $rop): RedirectResponse
    {
        $rop->delete();

        return redirect()
            ->route('admin.perhitungan.rop')
            ->with('success', 'Perhitungan ROP berhasil dihapus.');
    }

    private function calculateRop(int $leadTime, int $dailyDemand, int $safetyStock): int
    {
        return ($leadTime * $dailyDemand) + $safetyStock;
    }

}
