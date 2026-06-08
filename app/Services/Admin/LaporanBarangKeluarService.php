<?php

namespace App\Services\Admin;

use App\Models\BarangKeluar;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class LaporanBarangKeluarService
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
        return BarangKeluar::query()
            ->with([
                'onderdil:id,kode_onderdil,nama_onderdil',
            ])
            ->when($search !== '', fn (Builder $query) => $this->applySearchFilter($query, $search))
            ->when($tanggalAwal !== '', fn (Builder $query) => $query->whereDate('tanggal_keluar', '>=', $tanggalAwal))
            ->when($tanggalAkhir !== '', fn (Builder $query) => $query->whereDate('tanggal_keluar', '<=', $tanggalAkhir))
            ->orderByDesc('tanggal_keluar')
            ->orderByDesc('id');
    }

    public function paginate(string $search = '', string $tanggalAwal = '', string $tanggalAkhir = '', int $perPage = 10): LengthAwarePaginator
    {
        return $this->baseQuery($search, $tanggalAwal, $tanggalAkhir)->paginate($perPage)->withQueryString();
    }

    /**
     * @return Collection<int, BarangKeluar>
     */
    public function allForExport(string $search = '', string $tanggalAwal = '', string $tanggalAkhir = ''): Collection
    {
        return $this->baseQuery($search, $tanggalAwal, $tanggalAkhir)->get();
    }

    /**
     * @return array{
     *     total_transaksi: int,
     *     total_unit: int,
     *     total_jenis_onderdil: int,
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
            'total_jenis_onderdil' => $collection->pluck('onderdil_id')->filter()->unique()->count(),
        ];
    }

    private function applySearchFilter(Builder $query, string $search): Builder
    {
        return $query->whereHas('onderdil', function (Builder $onderdil) use ($search): void {
            $onderdil
                ->where('kode_onderdil', 'like', "%{$search}%")
                ->orWhere('nama_onderdil', 'like', "%{$search}%");
        });
    }
}
