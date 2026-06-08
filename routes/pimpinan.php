<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Pimpinan\AnalisisEoqController;
use App\Http\Controllers\Pimpinan\AnalisisRopController;
use App\Http\Controllers\Pimpinan\LaporanBarangKeluarController;
use App\Http\Controllers\Pimpinan\LaporanBarangMasukController;
use App\Http\Controllers\Pimpinan\LaporanEoqRopController;
use App\Http\Controllers\Pimpinan\LaporanPersediaanStokController;
use App\Http\Controllers\Pimpinan\PimpinanNotificationController;
use Illuminate\Support\Facades\Route;

Route::middleware(['internal.auth', 'role:pimpinan'])
    ->prefix('pimpinan')
    ->name('pimpinan.')
    ->group(function (): void {
        Route::get('/dashboard', [DashboardController::class, 'pimpinan'])->name('dashboard');

        Route::get('/notifikasi', [PimpinanNotificationController::class, 'index'])->name('notifikasi.index');

        Route::get('/analisis-persediaan/eoq', [AnalisisEoqController::class, 'index'])->name('analisis-persediaan.eoq');
        Route::get('/analisis-persediaan/rop', [AnalisisRopController::class, 'index'])->name('analisis-persediaan.rop');

        Route::get('/laporan/stok', [LaporanPersediaanStokController::class, 'index'])->name('laporan.stok');
        Route::get('/laporan/stok/cetak', [LaporanPersediaanStokController::class, 'print'])->name('laporan.stok.pdf');
        Route::get('/laporan/pembelian', [LaporanBarangMasukController::class, 'index'])->name('laporan.pembelian');
        Route::get('/laporan/pembelian/cetak', [LaporanBarangMasukController::class, 'print'])->name('laporan.pembelian.pdf');
        Route::get('/laporan/keluar', [LaporanBarangKeluarController::class, 'index'])->name('laporan.keluar');
        Route::get('/laporan/keluar/cetak', [LaporanBarangKeluarController::class, 'print'])->name('laporan.keluar.pdf');
        Route::get('/laporan/eoq-rop', [LaporanEoqRopController::class, 'index'])->name('laporan.eoq-rop');
        Route::get('/laporan/eoq-rop/cetak', [LaporanEoqRopController::class, 'print'])->name('laporan.eoq-rop.pdf');
    });
