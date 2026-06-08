<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\EoqRequest;
use App\Models\Eoq;
use App\Models\Onderdil;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EoqController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->string('search')->trim()->toString();

        $eoqs = Eoq::query()
            ->with('onderdil:id,kode_onderdil,nama_onderdil')
            ->when($search !== '', function ($query) use ($search): void {
                $query->where(function ($builder) use ($search): void {
                    $builder
                        ->where('kebutuhan_tahunan', 'like', "%{$search}%")
                        ->orWhere('biaya_pemesanan', 'like', "%{$search}%")
                        ->orWhere('biaya_penyimpanan', 'like', "%{$search}%")
                        ->orWhere('hasil_eoq', 'like', "%{$search}%")
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

        return view('admin.perhitungan.eoq', compact('eoqs', 'search'));
    }

    public function create(): View
    {
        $onderdils = Onderdil::orderBy('nama_onderdil')->get(['id', 'kode_onderdil', 'nama_onderdil']);

        return view('admin.perhitungan.eoq-form-page', [
            'eoq' => null,
            'onderdils' => $onderdils,
        ]);
    }

    public function store(EoqRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $validated['hasil_eoq'] = $this->calculateEoq(
            $validated['kebutuhan_tahunan'],
            $validated['biaya_pemesanan'],
            $validated['biaya_penyimpanan']
        );

        Eoq::create($validated);

        return redirect()
            ->route('admin.perhitungan.eoq')
            ->with('success', 'Perhitungan EOQ berhasil ditambahkan.');
    }

    public function edit(Eoq $eoq): View
    {
        $onderdils = Onderdil::orderBy('nama_onderdil')->get(['id', 'kode_onderdil', 'nama_onderdil']);

        return view('admin.perhitungan.eoq-form-page', compact('eoq', 'onderdils'));
    }

    public function update(EoqRequest $request, Eoq $eoq): RedirectResponse
    {
        $validated = $request->validated();
        $validated['hasil_eoq'] = $this->calculateEoq(
            $validated['kebutuhan_tahunan'],
            $validated['biaya_pemesanan'],
            $validated['biaya_penyimpanan']
        );

        $eoq->update($validated);

        return redirect()
            ->route('admin.perhitungan.eoq')
            ->with('success', 'Perhitungan EOQ berhasil diperbarui.');
    }

    public function destroy(Eoq $eoq): RedirectResponse
    {
        $eoq->delete();

        return redirect()
            ->route('admin.perhitungan.eoq')
            ->with('success', 'Perhitungan EOQ berhasil dihapus.');
    }

    private function calculateEoq(int $demand, int $orderingCost, int $holdingCost): int
    {
        $eoq = sqrt((2 * $demand * $orderingCost) / $holdingCost);

        return max(1, (int) round($eoq));
    }
}
