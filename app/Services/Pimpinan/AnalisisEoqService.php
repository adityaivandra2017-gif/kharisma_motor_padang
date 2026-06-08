<?php

namespace App\Services\Pimpinan;

use App\Models\Eoq;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class AnalisisEoqService
{
    public const FILTER_SEMUA = 'semua';

    public const FILTER_AMAN = 'aman';

    public const FILTER_PERLU_PEMBELIAN = 'perlu_pembelian';

    public const STATUS_AMAN = 'Aman';

    public const STATUS_PERLU_PEMBELIAN = 'Perlu Pembelian';

    /**
     * @return array{search: string, status: string}
     */
    public function resolveFilters(?string $search, ?string $status): array
    {
        $status = strtolower(trim((string) $status));

        if (! in_array($status, [self::FILTER_SEMUA, self::FILTER_AMAN, self::FILTER_PERLU_PEMBELIAN], true)) {
            $status = self::FILTER_SEMUA;
        }

        return [
            'search' => trim((string) $search),
            'status' => $status,
        ];
    }

    public function resolveStatus(int $stokSaatIni, int $hasilEoq): string
    {
        return $stokSaatIni < $hasilEoq
            ? self::STATUS_PERLU_PEMBELIAN
            : self::STATUS_AMAN;
    }

    public function rekomendasi(string $status, int $hasilEoq): string
    {
        if ($status === self::STATUS_PERLU_PEMBELIAN) {
            return 'Pesan '.number_format($hasilEoq, 0, ',', '.').' Unit';
        }

        return 'Stok mencukupi, tidak perlu pemesanan';
    }

    public function statusBadgeClasses(string $status): string
    {
        return $status === self::STATUS_PERLU_PEMBELIAN
            ? 'bg-amber-100 text-amber-900 ring-amber-200'
            : 'bg-emerald-100 text-emerald-800 ring-emerald-200';
    }

    private function baseQuery(string $search = '', string $status = self::FILTER_SEMUA): Builder
    {
        return Eoq::query()
            ->join('onderdil as o', 'o.id', '=', 'eoq.onderdil_id')
            ->select([
                'eoq.id',
                'eoq.onderdil_id',
                'eoq.hasil_eoq',
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
            ->when($status === self::FILTER_AMAN, fn (Builder $query) => $query->whereColumn('o.stok', '>=', 'eoq.hasil_eoq'))
            ->when($status === self::FILTER_PERLU_PEMBELIAN, fn (Builder $query) => $query->whereColumn('o.stok', '<', 'eoq.hasil_eoq'))
            ->orderByDesc('eoq.id');
    }

    public function paginate(string $search = '', string $status = self::FILTER_SEMUA, int $perPage = 10): LengthAwarePaginator
    {
        return $this->baseQuery($search, $status)->paginate($perPage)->withQueryString();
    }

    /**
     * @return Collection<int, Eoq>
     */
    public function allForSummary(string $search = '', string $status = self::FILTER_SEMUA): Collection
    {
        return $this->baseQuery($search, $status)->get();
    }

    /**
     * @return array{
     *     total_data: int,
     *     rata_rata: float,
     *     tertinggi: int,
     *     terendah: int,
     * }
     */
    public function summarize(Collection $items): array
    {
        if ($items->isEmpty()) {
            return [
                'total_data' => 0,
                'rata_rata' => 0,
                'tertinggi' => 0,
                'terendah' => 0,
            ];
        }

        $values = $items->pluck('hasil_eoq')->map(fn ($value) => (int) $value);

        return [
            'total_data' => $items->count(),
            'rata_rata' => round($values->avg(), 1),
            'tertinggi' => $values->max(),
            'terendah' => $values->min(),
        ];
    }
}
