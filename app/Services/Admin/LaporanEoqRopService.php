<?php

namespace App\Services\Admin;

use App\Models\Eoq;
use App\Models\Rop;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class LaporanEoqRopService
{
    public const FILTER_SEMUA = 'semua';

    public const FILTER_AMAN = 'aman';

    public const FILTER_PERLU_RESTOCK = 'perlu_restock';

    public const STATUS_AMAN = 'Aman';

    public const STATUS_PERLU_RESTOCK = 'Perlu Restock';

    /**
     * @return array{search: string, status: string}
     */
    public function resolveFilters(?string $search, ?string $status): array
    {
        $status = strtolower(trim((string) $status));

        if (! in_array($status, [self::FILTER_SEMUA, self::FILTER_AMAN, self::FILTER_PERLU_RESTOCK], true)) {
            $status = self::FILTER_SEMUA;
        }

        return [
            'search' => trim((string) $search),
            'status' => $status,
        ];
    }

    public function resolveStatus(int $stokSaatIni, int $hasilRop): string
    {
        return $stokSaatIni <= $hasilRop
            ? self::STATUS_PERLU_RESTOCK
            : self::STATUS_AMAN;
    }

    public function statusBadgeClasses(string $status): string
    {
        return $status === self::STATUS_PERLU_RESTOCK
            ? 'bg-red-100 text-red-800 ring-red-200'
            : 'bg-emerald-100 text-emerald-800 ring-emerald-200';
    }

    /**
     * @return array{dot: string, ring: string}
     */
    public function statusIndicator(string $status): array
    {
        return $status === self::STATUS_PERLU_RESTOCK
            ? ['dot' => 'bg-red-500', 'ring' => 'ring-red-100']
            : ['dot' => 'bg-emerald-500', 'ring' => 'ring-emerald-100'];
    }

    private function baseQuery(string $search = '', string $status = self::FILTER_SEMUA): Builder
    {
        $latestRopSub = Rop::query()
            ->select('onderdil_id', DB::raw('MAX(id) as latest_id'))
            ->groupBy('onderdil_id');

        $latestEoqSub = Eoq::query()
            ->select('onderdil_id', DB::raw('MAX(id) as latest_id'))
            ->groupBy('onderdil_id');

        return DB::table('onderdil as o')
            ->joinSub($latestRopSub, 'lr', 'lr.onderdil_id', '=', 'o.id')
            ->join('rop as r', 'r.id', '=', 'lr.latest_id')
            ->joinSub($latestEoqSub, 'le', 'le.onderdil_id', '=', 'o.id')
            ->join('eoq as e', 'e.id', '=', 'le.latest_id')
            ->select([
                'o.id',
                'o.kode_onderdil',
                'o.nama_onderdil',
                'o.stok as stok_saat_ini',
                'e.kebutuhan_tahunan',
                'e.biaya_pemesanan',
                'e.biaya_penyimpanan',
                'e.hasil_eoq',
                'r.lead_time',
                'r.safety_stock',
                'r.hasil_rop',
            ])
            ->when($search !== '', function (Builder $query) use ($search): void {
                $query->where(function (Builder $builder) use ($search): void {
                    $builder
                        ->where('o.kode_onderdil', 'like', "%{$search}%")
                        ->orWhere('o.nama_onderdil', 'like', "%{$search}%");
                });
            })
            ->when($status === self::FILTER_AMAN, fn (Builder $query) => $query->whereColumn('o.stok', '>', 'r.hasil_rop'))
            ->when($status === self::FILTER_PERLU_RESTOCK, fn (Builder $query) => $query->whereColumn('o.stok', '<=', 'r.hasil_rop'))
            ->orderBy('o.nama_onderdil');
    }

    public function paginate(string $search = '', string $status = self::FILTER_SEMUA, int $perPage = 10): LengthAwarePaginator
    {
        return $this->baseQuery($search, $status)->paginate($perPage)->withQueryString();
    }

    /**
     * @return Collection<int, object>
     */
    public function allForExport(string $search = '', string $status = self::FILTER_SEMUA): Collection
    {
        return $this->baseQuery($search, $status)->get();
    }

    /**
     * @return array{
     *     total_onderdil: int,
     *     total_aman: int,
     *     total_perlu_restock: int,
     * }
     */
    public function summarize(Collection|LengthAwarePaginator $items): array
    {
        $collection = $items instanceof LengthAwarePaginator
            ? $items->getCollection()
            : $items;

        $totalAman = 0;
        $totalPerluRestock = 0;

        foreach ($collection as $item) {
            if ($this->resolveStatus((int) $item->stok_saat_ini, (int) $item->hasil_rop) === self::STATUS_AMAN) {
                $totalAman++;
            } else {
                $totalPerluRestock++;
            }
        }

        return [
            'total_onderdil' => $collection->count(),
            'total_aman' => $totalAman,
            'total_perlu_restock' => $totalPerluRestock,
        ];
    }
}
