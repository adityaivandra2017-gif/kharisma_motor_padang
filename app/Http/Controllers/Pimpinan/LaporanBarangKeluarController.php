<?php

namespace App\Http\Controllers\Pimpinan;

use App\Http\Controllers\Controller;
use App\Services\Admin\LaporanBarangKeluarService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LaporanBarangKeluarController extends Controller
{
    public function __construct(
        private readonly LaporanBarangKeluarService $laporanService,
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
            $request->string('tanggal_awal')->toString(),
            $request->string('tanggal_akhir')->toString(),
        );

        $exportItems = $this->laporanService->allForExport(
            $filters['search'],
            $filters['tanggal_awal'],
            $filters['tanggal_akhir'],
        );

        return view('pimpinan.laporan.barang-keluar', [
            'barangKeluars' => $this->laporanService->paginate(
                $filters['search'],
                $filters['tanggal_awal'],
                $filters['tanggal_akhir'],
            ),
            'search' => $filters['search'],
            'tanggalAwal' => $filters['tanggal_awal'],
            'tanggalAkhir' => $filters['tanggal_akhir'],
            'ringkasan' => $this->laporanService->summarize($exportItems),
            'tanggalCetak' => $this->tanggalIndonesia(),
        ]);
    }

    public function print(Request $request): View
    {
        $filters = $this->laporanService->resolveFilters(
            $request->string('search')->toString(),
            $request->string('tanggal_awal')->toString(),
            $request->string('tanggal_akhir')->toString(),
        );

        $barangKeluars = $this->laporanService->allForExport(
            $filters['search'],
            $filters['tanggal_awal'],
            $filters['tanggal_akhir'],
        );

        return view('admin.laporan.print.keluar', [
            'barangKeluars' => $barangKeluars,
            'ringkasan' => $this->laporanService->summarize($barangKeluars),
            'dicetakOleh' => session('user_name', 'Pimpinan'),
            'jabatanPenandatangan' => $this->jabatanPenandatangan(),
            'kotaLaporan' => 'Padang',
        ]);
    }
}
