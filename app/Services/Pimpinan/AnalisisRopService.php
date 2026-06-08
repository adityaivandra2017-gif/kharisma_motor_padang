<?php

namespace App\Services\Pimpinan;

use App\Models\Rop;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class AnalisisRopService
{
    public const FILTER_SEMUA = 'semua';

    public const FILTER_AMAN = 'aman';

    public const FILTER_PERLU_RESTOCK = 'perlu_restock';

    public const FILTER_STOK_KRITIS = 'stok_kritis';

    public const STATUS_AMAN = 'Aman';

    public const STATUS_PERLU_RESTOCK = 'Perlu Restock';

    public const STATUS_STOK_KRITIS = 'Stok Kritis';

    /**
     * @return array{search: string, status: string}
     */
    public function resolveFilters(?string $search, ?string $status): array
    {
        $status = strtolower(trim((string) $status));

        if (! in_array($status, [
            self::FILTER_SEMUA,
            self::FILTER_AMAN,
            self::FILTER_PERLU_RESTOCK,
            self::FILTER_STOK_KRITIS,
        ], true)) {
            $status = self::FILTER_SEMUA;
        }

        return [
            'search' => trim((string) $search),
            'status' => $status,
        ];
    }

    public function resolveStatus(int $stokSaatIni, int $hasilRop): string
    {
        if ($stokSaatIni === 0) {
            return self::STATUS_STOK_KRITIS;
        }

        if ($stokSaatIni <= $hasilRop) {
            return self::STATUS_PERLU_RESTOCK;
        }

        return self::STATUS_AMAN;
    }

    public function statusBadgeClasses(string $status): string
    {
        return match ($status) {
            self::STATUS_STOK_KRITIS => 'bg-red-100 text-red-800 ring-red-200',
            self::STATUS_PERLU_RESTOCK => 'bg-amber-100 text-amber-900 ring-amber-200',
            default => 'bg-emerald-100 text-emerald-800 ring-emerald-200',
        };
    }

    public function statusDotClass(string $status): string
    {
        return match ($status) {
            self::STATUS_STOK_KRITIS => 'bg-red-500',
            self::STATUS_PERLU_RESTOCK => 'bg-amber-500',
            default => 'bg-emerald-500',
        };
    }

    private function baseQuery(string $search = '', string $status = self::FILTER_SEMUA): Builder
    {
        return Rop::query()
            ->join('onderdil as o', 'o.id', '=', 'rop.onderdil_id')
            ->select([
                'rop.id',
                'rop.onderdil_id',
                'rop.hasil_rop',
                'o.kode_onderdil',
                'o.nama_onderdil',
                'o.stok as stok_saat_ini',
            ])
            ->when($search !== '', function (Builder $query) use ($search): void {
                $query->where(function (Builder $builder) use ($search): void {
                    $builder
                        ->where('o.kode_onderdil', 'like', "%{$search}%")
                        ->orWhere('o.nama_onderdil', 'like', "%{$search}%");
                });
            })
            ->when($status === self::FILTER_AMAN, fn (Builder $query) => $query->whereColumn('o.stok', '>', 'rop.hasil_rop'))
            ->when($status === self::FILTER_PERLU_RESTOCK, fn (Builder $query) => $query
                ->where('o.stok', '>', 0)
                ->whereColumn('o.stok', '<=', 'rop.hasil_rop'))
            ->when($status === self::FILTER_STOK_KRITIS, fn (Builder $query) => $query->where('o.stok', '<=', 0))
            ->orderByDesc('rop.id');
    }

    public function paginate(string $search = '', string $status = self::FILTER_SEMUA, int $perPage = 10): LengthAwarePaginator
    {
        return $this->baseQuery($search, $status)->paginate($perPage)->withQueryString();
    }

    /**
     * @return Collection<int, Rop>
     */
    public function allForSummary(string $search = '', string $status = self::FILTER_SEMUA): Collection
    {
        return $this->baseQuery($search, $status)->get();
    }

    /**
     * @return Collection<int, Rop>
     */
    public function priorityRestock(string $search = '', int $limit = 5): Collection
    {
        return Rop::query()
            ->join('onderdil as o', 'o.id', '=', 'rop.onderdil_id')
            ->select([
                'rop.id',
                'rop.hasil_rop',
                'o.nama_onderdil',
                'o.stok as stok_saat_ini',
            ])
            ->whereColumn('o.stok', '<=', 'rop.hasil_rop')
            ->when($search !== '', function (Builder $query) use ($search): void {
                $query->where(function (Builder $builder) use ($search): void {
                    $builder
                        ->where('o.kode_onderdil', 'like', "%{$search}%")
                        ->orWhere('o.nama_onderdil', 'like', "%{$search}%");
                });
            })
            ->orderBy('o.stok')
            ->orderBy('rop.hasil_rop')
            ->limit($limit)
            ->get();
    }

    /**
     * @return array{
     *     total_data: int,
     *     total_aman: int,
     *     total_perlu_restock: int,
     *     total_stok_kritis: int,
     * }
     */
    public function summarize(Collection $items): array
    {
        $totalAman = 0;
        $totalPerluRestock = 0;
        $totalStokKritis = 0;

        foreach ($items as $item) {
            $status = $this->resolveStatus((int) $item->stok_saat_ini, (int) $item->hasil_rop);

            match ($status) {
                self::STATUS_AMAN => $totalAman++,
                self::STATUS_PERLU_RESTOCK => $totalPerluRestock++,
                self::STATUS_STOK_KRITIS => $totalStokKritis++,
                default => null,
            };
        }

        return [
            'total_data' => $items->count(),
            'total_aman' => $totalAman,
            'total_perlu_restock' => $totalPerluRestock,
            'total_stok_kritis' => $totalStokKritis,
        ];
    }
}
