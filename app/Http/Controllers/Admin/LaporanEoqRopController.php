<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Admin\LaporanEoqRopService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LaporanEoqRopController extends Controller
{
    public function __construct(
        private readonly LaporanEoqRopService $laporanService,
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
        return session('user_role') === 'pimpinan' ? 'Pimpinan' : 'Admin Sistem';
    }

    public function index(Request $request): View
    {
        $filters = $this->laporanService->resolveFilters(
            $request->string('search')->toString(),
            $request->string('status')->toString(),
        );

        $exportItems = $this->laporanService->allForExport(
            $filters['search'],
            $filters['status'],
        );

        return view('admin.laporan.eoq-rop', [
            'analisis' => $this->laporanService->paginate(
                $filters['search'],
                $filters['status'],
            ),
            'search' => $filters['search'],
            'status' => $filters['status'],
            'ringkasan' => $this->laporanService->summarize($exportItems),
            'tanggalCetak' => $this->tanggalIndonesia(),
            'laporanService' => $this->laporanService,
        ]);
    }

    public function print(Request $request): View
    {
        $filters = $this->laporanService->resolveFilters(
            $request->string('search')->toString(),
            $request->string('status')->toString(),
        );

        $analisis = $this->laporanService->allForExport(
            $filters['search'],
            $filters['status'],
        );

        return view('admin.laporan.print.eoq-rop', [
            'analisis' => $analisis,
            'ringkasan' => $this->laporanService->summarize($analisis),
            'dicetakOleh' => session('user_name', 'Admin'),
            'jabatanPenandatangan' => $this->jabatanPenandatangan(),
            'kotaLaporan' => 'Padang',
        ]);
    }
}
