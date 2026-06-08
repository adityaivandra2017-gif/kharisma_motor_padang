<?php

namespace App\Services\Admin;

use App\Models\Rop;
use Illuminate\Support\Collection;

class StockNotificationService
{
    /**
     * Ambil peringatan stok berdasarkan perhitungan ROP (status bukan Aman).
     *
     * @return Collection<int, StockAlert>
     */
    public function getAlerts(): Collection
    {
        return Rop::query()
            ->with(['onderdil:id,kode_onderdil,nama_onderdil,stok,updated_at'])
            ->orderBy('id')
            ->get()
            ->map(function (Rop $rop): ?StockAlert {
                if ($rop->onderdil === null) {
                    return null;
                }

                $currentStock = (int) $rop->onderdil->stok;
                $hasilRop = (int) $rop->hasil_rop;
                $safetyStock = (int) $rop->safety_stock;
                $status = Rop::resolveLiveStockStatus($currentStock, $hasilRop, $safetyStock);

                if ($status === Rop::STATUS_AMAN) {
                    return null;
                }

                $occurredAt = $rop->updated_at;
                if ($rop->onderdil->updated_at !== null && $rop->onderdil->updated_at->gt($occurredAt)) {
                    $occurredAt = $rop->onderdil->updated_at;
                }

                return new StockAlert(
                    ropId: $rop->id,
                    kodeOnderdil: $rop->onderdil->kode_onderdil,
                    namaOnderdil: $rop->onderdil->nama_onderdil,
                    currentStock: $currentStock,
                    hasilRop: $hasilRop,
                    status: $status,
                    occurredAt: $occurredAt,
                );
            })
            ->filter()
            ->sortBy(fn (StockAlert $alert) => $alert->status === Rop::STATUS_SEGERA_RESTOCK ? 0 : 1)
            ->values();
    }

    public function getAlertCount(): int
    {
        return $this->getAlerts()->count();
    }
}
