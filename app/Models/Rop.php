<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'onderdil_id',
    'lead_time',
    'kebutuhan_per_hari',
    'safety_stock',
    'hasil_rop',
    'status_stok',
    'keterangan',
])]
class Rop extends Model
{
    protected $table = 'rop';

    public const STATUS_AMAN = 'Aman';
    public const STATUS_MENIPIS = 'Menipis';
    public const STATUS_SEGERA_RESTOCK = 'Segera Restock';

    public function onderdil(): BelongsTo
    {
        return $this->belongsTo(Onderdil::class);
    }

    public static function resolveLiveStockStatus(int $currentStock, int $hasilRop, int $safetyStock): string
    {
        if ($currentStock <= $hasilRop) {
            return self::STATUS_SEGERA_RESTOCK;
        }

        if ($currentStock <= ($hasilRop + $safetyStock)) {
            return self::STATUS_MENIPIS;
        }

        return self::STATUS_AMAN;
    }

    public static function statusBadgeClasses(string $status): string
    {
        return match ($status) {
            self::STATUS_SEGERA_RESTOCK => 'bg-red-100 text-red-800 ring-red-200',
            self::STATUS_MENIPIS => 'bg-amber-100 text-amber-800 ring-amber-200',
            default => 'bg-emerald-100 text-emerald-800 ring-emerald-200',
        };
    }

    /**
     * @return array{dot: string, ring: string, emoji: string, label: string}
     */
    public static function statusIndicator(string $status): array
    {
        return match ($status) {
            self::STATUS_SEGERA_RESTOCK => [
                'dot' => 'bg-red-500',
                'ring' => 'ring-red-100',
                'emoji' => '🔴',
                'label' => self::STATUS_SEGERA_RESTOCK,
            ],
            self::STATUS_MENIPIS => [
                'dot' => 'bg-amber-400',
                'ring' => 'ring-amber-100',
                'emoji' => '🟡',
                'label' => self::STATUS_MENIPIS,
            ],
            default => [
                'dot' => 'bg-emerald-500',
                'ring' => 'ring-emerald-100',
                'emoji' => '🟢',
                'label' => self::STATUS_AMAN,
            ],
        };
    }
}
