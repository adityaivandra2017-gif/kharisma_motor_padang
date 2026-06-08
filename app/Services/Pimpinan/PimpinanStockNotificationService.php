<?php

namespace App\Services\Pimpinan;

use App\Models\Rop;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class PimpinanStockNotificationService
{
    /**
     * @return Collection<int, PimpinanStockAlert>
     */
    public function getAlerts(): Collection
    {
        $latestRopSub = Rop::query()
            ->select('onderdil_id', DB::raw('MAX(id) as latest_id'))
            ->groupBy('onderdil_id');

        return Rop::query()
            ->joinSub($latestRopSub, 'lr', 'lr.onderdil_id', '=', 'rop.onderdil_id')
            ->whereColumn('rop.id', 'lr.latest_id')
            ->join('onderdil as o', 'o.id', '=', 'rop.onderdil_id')
            ->select([
                'rop.id as rop_id',
                'rop.hasil_rop',
                'rop.updated_at as rop_updated_at',
                'o.kode_onderdil',
                'o.nama_onderdil',
                'o.stok',
                'o.updated_at as onderdil_updated_at',
            ])
            ->orderBy('o.nama_onderdil')
            ->get()
            ->map(function (object $row): ?PimpinanStockAlert {
                $currentStock = (int) $row->stok;
                $hasilRop = (int) $row->hasil_rop;

                if ($currentStock === 0) {
                    $status = PimpinanStockAlert::STATUS_STOK_HABIS;
                } elseif ($currentStock <= $hasilRop) {
                    $status = PimpinanStockAlert::STATUS_PERLU_RESTOCK;
                } else {
                    return null;
                }

                $ropUpdated = Carbon::parse($row->rop_updated_at);
                $onderdilUpdated = $row->onderdil_updated_at !== null
                    ? Carbon::parse($row->onderdil_updated_at)
                    : $ropUpdated;
                $occurredAt = $onderdilUpdated->gt($ropUpdated) ? $onderdilUpdated : $ropUpdated;

                return new PimpinanStockAlert(
                    ropId: (int) $row->rop_id,
                    kodeOnderdil: $row->kode_onderdil,
                    namaOnderdil: $row->nama_onderdil,
                    currentStock: $currentStock,
                    hasilRop: $hasilRop,
                    status: $status,
                    occurredAt: $occurredAt,
                );
            })
            ->filter()
            ->sortBy(function (PimpinanStockAlert $alert): array {
                return [
                    $alert->isStokHabis() ? 0 : 1,
                    $alert->currentStock,
                ];
            })
            ->values();
    }

    public function getAlertCount(): int
    {
        return $this->getAlerts()->count();
    }
}
