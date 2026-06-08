<?php

namespace App\Http\Controllers\Pimpinan;

use App\Http\Controllers\Controller;
use App\Services\Admin\LaporanPersediaanStokService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LaporanPersediaanStokController extends Controller
{
    public function __construct(
        private readonly LaporanPersediaanStokService $laporanService,
    ) {}

    private function tanggalIndonesia(): string
    {
        return now()
            ->timezone('Asia/Jakarta')
            ->locale('id')
            ->translatedFormat('d F Y');
    }

    private function jabatanPenandatangan(): string
    {
        return 'Pimpinan';
    }

    public function index(Request $request): View
    {
        $filters = $this->laporanService->resolveFilters(
            $request->string('search')->toString(),
            $request->string('status')->toString(),
        );

        $onderdils = $this->laporanService->paginate($filters['search'], $filters['status']);
        $ringkasan = $this->laporanService->summarize(
            $this->laporanService->allForExport($filters['search'], $filters['status']),
        );

        return view('pimpinan.laporan.persediaan-stok', [
            'onderdils' => $onderdils,
            'search' => $filters['search'],
            'status' => $filters['status'],
            'ringkasan' => $ringkasan,
            'tanggalCetak' => $this->tanggalIndonesia(),
        ]);
    }

    public function print(Request $request): View
    {
        $filters = $this->laporanService->resolveFilters(
            $request->string('search')->toString(),
            $request->string('status')->toString(),
        );

        $onderdils = $this->laporanService->allForExport($filters['search'], $filters['status']);
        $ringkasan = $this->laporanService->summarize($onderdils);

        return view('admin.laporan.print.stok', [
            'onderdils' => $onderdils,
            'search' => $filters['search'],
            'status' => $filters['status'],
            'ringkasan' => $ringkasan,
            'dicetakOleh' => session('user_name', 'Pimpinan'),
            'jabatanPenandatangan' => $this->jabatanPenandatangan(),
            'kotaLaporan' => 'Padang',
        ]);
    }
}
