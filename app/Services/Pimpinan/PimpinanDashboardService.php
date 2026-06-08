<?php

namespace App\Services\Pimpinan;

use App\Models\BarangKeluar;
use App\Models\BarangMasuk;
use App\Models\Eoq;
use App\Models\Onderdil;
use App\Models\Rop;
use App\Models\Supplier;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class PimpinanDashboardService
{
    public function greeting(): string
    {
        $hour = (int) now()->timezone('Asia/Jakarta')->format('G');

        return match (true) {
            $hour >= 5 && $hour < 11 => 'Selamat Pagi',
            $hour >= 11 && $hour < 15 => 'Selamat Siang',
            $hour >= 15 && $hour < 19 => 'Selamat Sore',
            default => 'Selamat Malam',
        };
    }

    /**
     * @return array{
     *     total_onderdil: int,
     *     total_supplier: int,
     *     total_barang_masuk: int,
     *     total_barang_keluar: int,
     * }
     */
    public function summaryStats(): array
    {
        return [
            'total_onderdil' => Onderdil::query()->count(),
            'total_supplier' => Supplier::query()->count(),
            'total_barang_masuk' => BarangMasuk::query()->count(),
            'total_barang_keluar' => BarangKeluar::query()->count(),
        ];
    }

    /**
     * @return array{aman: int, menipis: int, habis: int, total: int}
     */
    public function stockStatusCounts(): array
    {
        $aman = Onderdil::query()->whereColumn('stok', '>', 'stok_minimum')->count();
        $menipis = Onderdil::query()->where('stok', '>', 0)->whereColumn('stok', '<=', 'stok_minimum')->count();
        $habis = Onderdil::query()->where('stok', '<=', 0)->count();

        return [
            'aman' => $aman,
            'menipis' => $menipis,
            'habis' => $habis,
            'total' => $aman + $menipis + $habis,
        ];
    }

    /**
     * @return array{
     *     labels: list<string>,
     *     values: list<int>,
     *     percentages: list<float>,
     *     legends: array<int, array{label: string, value: int, percent: float, color: string, dot: string}>,
     * }
     */
    public function stockDoughnutData(): array
    {
        $counts = $this->stockStatusCounts();
        $total = max($counts['total'], 1);

        $labels = ['Aman', 'Menipis', 'Habis'];
        $values = [$counts['aman'], $counts['menipis'], $counts['habis']];
        $percentages = array_map(fn (int $value): float => round(($value / $total) * 100, 1), $values);

        return [
            'labels' => $labels,
            'values' => $values,
            'percentages' => $percentages,
            'legends' => [
                [
                    'label' => 'Aman',
                    'value' => $counts['aman'],
                    'percent' => $percentages[0],
                    'color' => 'emerald',
                    'dot' => '🟢',
                ],
                [
                    'label' => 'Menipis',
                    'value' => $counts['menipis'],
                    'percent' => $percentages[1],
                    'color' => 'amber',
                    'dot' => '🟡',
                ],
                [
                    'label' => 'Habis',
                    'value' => $counts['habis'],
                    'percent' => $percentages[2],
                    'color' => 'red',
                    'dot' => '🔴',
                ],
            ],
        ];
    }

    public function totalPerluRestock(): int
    {
        $latestRopSub = Rop::query()
            ->select('onderdil_id', DB::raw('MAX(id) as latest_id'))
            ->groupBy('onderdil_id');

        return (int) DB::table('rop as r')
            ->joinSub($latestRopSub, 'lr', fn ($join) => $join->on('r.id', '=', 'lr.latest_id'))
            ->join('onderdil as o', 'o.id', '=', 'r.onderdil_id')
            ->whereColumn('o.stok', '<=', 'r.hasil_rop')
            ->count();
    }

    /**
     * @return array{total_eoq: int, total_rop: int, total_perlu_restock: int}
     */
    public function analysisSummary(): array
    {
        return [
            'total_eoq' => Eoq::query()->count(),
            'total_rop' => Rop::query()->count(),
            'total_perlu_restock' => $this->totalPerluRestock(),
        ];
    }

    /**
     * @return Collection<int, object>
     */
    public function attentionItems(int $limit = 5): Collection
    {
        $latestRopSub = Rop::query()
            ->select('onderdil_id', DB::raw('MAX(id) as latest_id'))
            ->groupBy('onderdil_id');

        return DB::table('rop as r')
            ->joinSub($latestRopSub, 'lr', fn ($join) => $join->on('r.id', '=', 'lr.latest_id'))
            ->join('onderdil as o', 'o.id', '=', 'r.onderdil_id')
            ->whereColumn('o.stok', '<=', 'r.hasil_rop')
            ->orderByRaw('(r.hasil_rop - o.stok) DESC')
            ->orderByDesc('r.updated_at')
            ->limit($limit)
            ->get([
                'o.nama_onderdil',
                'o.stok as stok_saat_ini',
                'r.hasil_rop as nilai_rop',
            ])
            ->map(function (object $item): object {
                $item->status = 'Perlu Restock';
                return $item;
            });
    }
}
