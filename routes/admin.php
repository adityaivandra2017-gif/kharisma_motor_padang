<?php

use App\Http\Controllers\Admin\LaporanBarangKeluarController;
use App\Http\Controllers\Admin\LaporanBarangMasukController;
use App\Http\Controllers\Admin\LaporanEoqRopController;
use App\Http\Controllers\Admin\LaporanPersediaanStokController;
use App\Http\Controllers\Admin\OnderdilController;
use App\Http\Controllers\Admin\BarangKeluarController;
use App\Http\Controllers\Admin\BarangMasukController;
use App\Http\Controllers\Admin\EoqController;
use App\Http\Controllers\Admin\RopController;
use App\Http\Controllers\Admin\SupplierController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

Route::middleware(['internal.auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function (): void {
        Route::get('/dashboard', [DashboardController::class, 'admin'])->name('dashboard');

        Route::resource('master-data/onderdil', OnderdilController::class)
            ->except(['show'])
            ->parameters(['onderdil' => 'onderdil'])
            ->names([
                'index' => 'master-data.onderdil',
                'create' => 'master-data.onderdil.create',
                'store' => 'master-data.onderdil.store',
                'edit' => 'master-data.onderdil.edit',
                'update' => 'master-data.onderdil.update',
                'destroy' => 'master-data.onderdil.destroy',
            ]);
        Route::resource('master-data/supplier', SupplierController::class)
            ->except(['show'])
            ->parameters(['supplier' => 'supplier'])
            ->names([
                'index' => 'master-data.supplier',
                'create' => 'master-data.supplier.create',
                'store' => 'master-data.supplier.store',
                'edit' => 'master-data.supplier.edit',
                'update' => 'master-data.supplier.update',
                'destroy' => 'master-data.supplier.destroy',
            ]);

        Route::resource('transaksi/masuk', BarangMasukController::class)
            ->except(['show'])
            ->parameters(['masuk' => 'masuk'])
            ->names([
                'index' => 'transaksi.masuk',
                'create' => 'transaksi.masuk.create',
                'store' => 'transaksi.masuk.store',
                'edit' => 'transaksi.masuk.edit',
                'update' => 'transaksi.masuk.update',
                'destroy' => 'transaksi.masuk.destroy',
            ]);
        Route::resource('transaksi/keluar', BarangKeluarController::class)
            ->except(['show'])
            ->parameters(['keluar' => 'keluar'])
            ->names([
                'index' => 'transaksi.keluar',
                'create' => 'transaksi.keluar.create',
                'store' => 'transaksi.keluar.store',
                'edit' => 'transaksi.keluar.edit',
                'update' => 'transaksi.keluar.update',
                'destroy' => 'transaksi.keluar.destroy',
            ]);

        Route::resource('perhitungan/eoq', EoqController::class)
            ->except(['show'])
            ->parameters(['eoq' => 'eoq'])
            ->names([
                'index' => 'perhitungan.eoq',
                'create' => 'perhitungan.eoq.create',
                'store' => 'perhitungan.eoq.store',
                'edit' => 'perhitungan.eoq.edit',
                'update' => 'perhitungan.eoq.update',
                'destroy' => 'perhitungan.eoq.destroy',
            ]);
        Route::resource('perhitungan/rop', RopController::class)
            ->except(['show'])
            ->parameters(['rop' => 'rop'])
            ->names([
                'index' => 'perhitungan.rop',
                'create' => 'perhitungan.rop.create',
                'store' => 'perhitungan.rop.store',
                'edit' => 'perhitungan.rop.edit',
                'update' => 'perhitungan.rop.update',
                'destroy' => 'perhitungan.rop.destroy',
            ]);

        Route::get('/laporan/stok', [LaporanPersediaanStokController::class, 'index'])->name('laporan.stok');
        Route::get('/laporan/stok/cetak', [LaporanPersediaanStokController::class, 'print'])->name('laporan.stok.pdf');
        Route::get('/laporan/masuk', [LaporanBarangMasukController::class, 'index'])->name('laporan.masuk');
        Route::get('/laporan/masuk/cetak', [LaporanBarangMasukController::class, 'print'])->name('laporan.masuk.pdf');
        Route::get('/laporan/keluar', [LaporanBarangKeluarController::class, 'index'])->name('laporan.keluar');
        Route::get('/laporan/keluar/cetak', [LaporanBarangKeluarController::class, 'print'])->name('laporan.keluar.pdf');
        Route::get('/laporan/eoq-rop', [LaporanEoqRopController::class, 'index'])->name('laporan.eoq-rop');
        Route::get('/laporan/eoq-rop/cetak', [LaporanEoqRopController::class, 'print'])->name('laporan.eoq-rop.pdf');
    });
