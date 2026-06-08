<?php

namespace App\Services\Admin;

use App\Models\BarangMasuk;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class LaporanBarangMasukService
{
    /**
     * @return array{search: string, tanggal_awal: string, tanggal_akhir: string}
     */
    public function resolveFilters(?string $search, ?string $tanggalAwal, ?string $tanggalAkhir): array
    {
        $tanggalAwal = trim((string) $tanggalAwal);
        $tanggalAkhir = trim((string) $tanggalAkhir);

        if ($tanggalAwal !== '' && $tanggalAkhir !== '' && $tanggalAwal > $tanggalAkhir) {
            [$tanggalAwal, $tanggalAkhir] = [$tanggalAkhir, $tanggalAwal];
        }

        return [
            'search' => trim((string) $search),
            'tanggal_awal' => $tanggalAwal,
            'tanggal_akhir' => $tanggalAkhir,
        ];
    }

    public function baseQuery(string $search = '', string $tanggalAwal = '', string $tanggalAkhir = ''): Builder
    {
        return BarangMasuk::query()
            ->with([
                'onderdil:id,kode_onderdil,nama_onderdil',
                'supplier:id,nama_supplier',
            ])
            ->when($search !== '', fn (Builder $query) => $this->applySearchFilter($query, $search))
            ->when($tanggalAwal !== '', fn (Builder $query) => $query->whereDate('tanggal_masuk', '>=', $tanggalAwal))
            ->when($tanggalAkhir !== '', fn (Builder $query) => $query->whereDate('tanggal_masuk', '<=', $tanggalAkhir))
            ->orderByDesc('tanggal_masuk')
            ->orderByDesc('id');
    }

    public function paginate(string $search = '', string $tanggalAwal = '', string $tanggalAkhir = '', int $perPage = 10): LengthAwarePaginator
    {
        return $this->baseQuery($search, $tanggalAwal, $tanggalAkhir)->paginate($perPage)->withQueryString();
    }

    /**
     * @return Collection<int, BarangMasuk>
     */
    public function allForExport(string $search = '', string $tanggalAwal = '', string $tanggalAkhir = ''): Collection
    {
        return $this->baseQuery($search, $tanggalAwal, $tanggalAkhir)->get();
    }

    /**
     * @return array{
     *     total_transaksi: int,
     *     total_unit: int,
     *     total_supplier_aktif: int,
     * }
     */
    public function summarize(Collection|LengthAwarePaginator $items): array
    {
        $collection = $items instanceof LengthAwarePaginator
            ? $items->getCollection()
            : $items;

        return [
            'total_transaksi' => $collection->count(),
            'total_unit' => (int) $collection->sum('jumlah'),
            'total_supplier_aktif' => $collection->pluck('supplier_id')->filter()->unique()->count(),
        ];
    }

    private function applySearchFilter(Builder $query, string $search): Builder
    {
        return $query->where(function (Builder $builder) use ($search): void {
            $builder
                ->whereHas('onderdil', function (Builder $onderdil) use ($search): void {
                    $onderdil
                        ->where('kode_onderdil', 'like', "%{$search}%")
                        ->orWhere('nama_onderdil', 'like', "%{$search}%");
                })
                ->orWhereHas('supplier', fn (Builder $supplier) => $supplier->where('nama_supplier', 'like', "%{$search}%"));
        });
    }
}
