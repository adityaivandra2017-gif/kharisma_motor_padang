<?php

namespace App\Http\Controllers;

use App\Services\Admin\AdminDashboardService;
use App\Services\Pimpinan\PimpinanDashboardService;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __construct(
        private readonly AdminDashboardService $dashboardService,
        private readonly PimpinanDashboardService $pimpinanDashboardService,
    ) {}

    public function admin(): View
    {
        $stockStatus = $this->dashboardService->stockStatusCounts();

        return view('admin.dashboard', [
            'greeting' => $this->dashboardService->greeting(),
            'userName' => session('user_name', 'Admin'),
            'stats' => $this->dashboardService->summaryStats(),
            'stockStatus' => $stockStatus,
            'stockChart' => $this->dashboardService->stockChartData(),
            'recentActivities' => $this->dashboardService->recentActivities(),
        ]);
    }

    public function pimpinan(): View
    {
        $stockStatus = $this->pimpinanDashboardService->stockStatusCounts();

        return view('pimpinan.dashboard', [
            'greeting' => $this->pimpinanDashboardService->greeting(),
            'userName' => session('user_name', 'Pimpinan'),
            'stats' => $this->pimpinanDashboardService->summaryStats(),
            'stockStatus' => $stockStatus,
            'stockChart' => $this->pimpinanDashboardService->stockDoughnutData(),
            'analysis' => $this->pimpinanDashboardService->analysisSummary(),
            'attentionItems' => $this->pimpinanDashboardService->attentionItems(5),
        ]);
    }
}
