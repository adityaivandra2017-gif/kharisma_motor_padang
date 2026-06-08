<?php

namespace App\Services\Admin;

use App\Models\BarangKeluar;
use App\Models\BarangMasuk;
use App\Models\Eoq;
use App\Models\Onderdil;
use App\Models\Rop;
use App\Models\Supplier;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class AdminDashboardService
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
     * @return array{aman: int, menipis: int, habis: int}
     */
    public function stockStatusCounts(): array
    {
        return [
            'aman' => Onderdil::query()->whereColumn('stok', '>', 'stok_minimum')->count(),
            'menipis' => Onderdil::query()->where('stok', '>', 0)->whereColumn('stok', '<=', 'stok_minimum')->count(),
            'habis' => Onderdil::query()->where('stok', '<=', 0)->count(),
        ];
    }

    /**
     * @return array{labels: list<string>, values: list<int>}
     */
    public function stockChartData(): array
    {
        $counts = $this->stockStatusCounts();

        return [
            'labels' => ['Aman', 'Menipis', 'Habis'],
            'values' => [$counts['aman'], $counts['menipis'], $counts['habis']],
        ];
    }

    /**
     * @return Collection<int, array{nama_onderdil: string, jenis: string, tanggal: Carbon, sort: int}>
     */
    public function recentActivities(int $limit = 5): Collection
    {
        $items = collect();

        BarangMasuk::query()
            ->with('onderdil:id,nama_onderdil')
            ->orderByDesc('tanggal_masuk')
            ->orderByDesc('id')
            ->limit($limit)
            ->get()
            ->each(function (BarangMasuk $masuk) use ($items): void {
                $items->push([
                    'nama_onderdil' => $masuk->onderdil?->nama_onderdil ?? '-',
                    'jenis' => 'Barang Masuk',
                    'tanggal' => $masuk->tanggal_masuk,
                    'sort' => $masuk->tanggal_masuk->startOfDay()->timestamp * 1000 + $masuk->id,
                ]);
            });

        BarangKeluar::query()
            ->with('onderdil:id,nama_onderdil')
            ->orderByDesc('tanggal_keluar')
            ->orderByDesc('id')
            ->limit($limit)
            ->get()
            ->each(function (BarangKeluar $keluar) use ($items): void {
                $items->push([
                    'nama_onderdil' => $keluar->onderdil?->nama_onderdil ?? '-',
                    'jenis' => 'Barang Keluar',
                    'tanggal' => $keluar->tanggal_keluar,
                    'sort' => $keluar->tanggal_keluar->startOfDay()->timestamp * 1000 + $keluar->id,
                ]);
            });

        Eoq::query()
            ->with('onderdil:id,nama_onderdil')
            ->orderByDesc('created_at')
            ->orderByDesc('id')
            ->limit($limit)
            ->get()
            ->each(function (Eoq $eoq) use ($items): void {
                $items->push([
                    'nama_onderdil' => $eoq->onderdil?->nama_onderdil ?? '-',
                    'jenis' => 'Perhitungan EOQ',
                    'tanggal' => $eoq->created_at,
                    'sort' => $eoq->created_at?->timestamp ?? 0,
                ]);
            });

        Rop::query()
            ->with('onderdil:id,nama_onderdil')
            ->orderByDesc('created_at')
            ->orderByDesc('id')
            ->limit($limit)
            ->get()
            ->each(function (Rop $rop) use ($items): void {
                $items->push([
                    'nama_onderdil' => $rop->onderdil?->nama_onderdil ?? '-',
                    'jenis' => 'Perhitungan ROP',
                    'tanggal' => $rop->created_at,
                    'sort' => $rop->created_at?->timestamp ?? 0,
                ]);
            });

        return $items
            ->sortByDesc('sort')
            ->take($limit)
            ->values()
            ->map(function (array $item): array {
                $tanggal = $item['tanggal'];
                $item['tanggal_label'] = $tanggal instanceof Carbon
                    ? $tanggal->timezone('Asia/Jakarta')->format('d-m-Y')
                    : '-';

                return $item;
            });
    }
}
