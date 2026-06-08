<?php

namespace App\Providers;

use App\Services\Admin\StockNotificationService;
use App\Services\Pimpinan\PimpinanStockNotificationService;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('partials.shared.notification', function ($view): void {
            $prefix = $view->getData()['prefix'] ?? 'admin';

            $alerts = match ($prefix) {
                'admin' => app(StockNotificationService::class)->getAlerts(),
                'pimpinan' => app(PimpinanStockNotificationService::class)->getAlerts(),
                default => collect(),
            };

            $view->with([
                'notificationPanelMode' => $prefix,
                'stockNotifications' => $alerts,
                'stockNotificationCount' => $alerts->count(),
            ]);
        });
    }
}
