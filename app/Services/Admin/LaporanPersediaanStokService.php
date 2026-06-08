<?php

namespace App\Services\Admin;

use App\Models\Onderdil;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class LaporanPersediaanStokService
{
    public const FILTER_SEMUA = 'semua';

    /**
     * @return array{search: string, status: string}
     */
    public function resolveFilters(?string $search, ?string $status): array
    {
        $status = strtolower(trim((string) $status));

        if (! in_array($status, [self::FILTER_SEMUA, Onderdil::STATUS_AMAN, Onderdil::STATUS_MENIPIS, Onderdil::STATUS_HABIS], true)) {
            $status = self::FILTER_SEMUA;
        }

        return [
            'search' => trim((string) $search),
            'status' => $status,
        ];
    }

    public function baseQuery(string $search = '', string $status = self::FILTER_SEMUA): Builder
    {
        return Onderdil::query()
            ->with('supplier:id,nama_supplier')
            ->when($search !== '', fn (Builder $query) => $query->where('nama_onderdil', 'like', "%{$search}%"))
            ->when($status !== self::FILTER_SEMUA, fn (Builder $query) => $this->applyStatusFilter($query, $status))
            ->orderBy('nama_onderdil');
    }

    public function paginate(string $search = '', string $status = self::FILTER_SEMUA, int $perPage = 10): LengthAwarePaginator
    {
        return $this->baseQuery($search, $status)->paginate($perPage)->withQueryString();
    }

    /**
     * @return Collection<int, Onderdil>
     */
    public function allForExport(string $search = '', string $status = self::FILTER_SEMUA): Collection
    {
        return $this->baseQuery($search, $status)->get();
    }

    /**
     * @return array{
     *     total_onderdil: int,
     *     total_aman: int,
     *     total_menipis: int,
     *     total_habis: int,
     * }
     */
    public function summarize(Collection|LengthAwarePaginator $items): array
    {
        $collection = $items instanceof LengthAwarePaginator
            ? $items->getCollection()
            : $items;

        return [
            'total_onderdil' => $collection->count(),
            'total_aman' => $collection->where('status_stok', Onderdil::STATUS_AMAN)->count(),
            'total_menipis' => $collection->where('status_stok', Onderdil::STATUS_MENIPIS)->count(),
            'total_habis' => $collection->where('status_stok', Onderdil::STATUS_HABIS)->count(),
        ];
    }

    private function applyStatusFilter(Builder $query, string $status): Builder
    {
        return match ($status) {
            Onderdil::STATUS_HABIS => $query->where('stok', '<=', 0),
            Onderdil::STATUS_MENIPIS => $query->where('stok', '>', 0)->whereColumn('stok', '<=', 'stok_minimum'),
            Onderdil::STATUS_AMAN => $query->whereColumn('stok', '>', 'stok_minimum'),
            default => $query,
        };
    }
}
