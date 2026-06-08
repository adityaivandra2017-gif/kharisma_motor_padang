<?php

namespace App\Http\Controllers\Pimpinan;

use App\Http\Controllers\Controller;
use App\Services\Pimpinan\PimpinanStockAlert;
use App\Services\Pimpinan\PimpinanStockNotificationService;
use Illuminate\View\View;

class PimpinanNotificationController extends Controller
{
    public function __construct(
        private readonly PimpinanStockNotificationService $notificationService,
    ) {}

    public function index(): View
    {
        $notifications = $this->notificationService->getAlerts();

        return view('pimpinan.notifikasi.index', [
            'notifications' => $notifications,
            'totalAktif' => $notifications->count(),
            'totalStokHabis' => $notifications->where('status', PimpinanStockAlert::STATUS_STOK_HABIS)->count(),
            'totalPerluRestock' => $notifications->where('status', PimpinanStockAlert::STATUS_PERLU_RESTOCK)->count(),
        ]);
    }
}
