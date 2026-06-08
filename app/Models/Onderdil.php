<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'kode_onderdil',
    'nama_onderdil',
    'jenis',
    'harga',
    'stok',
    'stok_minimum',
    'supplier_id',
    'deskripsi',
])]
class Onderdil extends Model
{
    protected $table = 'onderdil';

    public const STATUS_AMAN = 'aman';

    public const STATUS_MENIPIS = 'menipis';

    public const STATUS_HABIS = 'habis';

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    public function barangMasuks(): HasMany
    {
        return $this->hasMany(BarangMasuk::class);
    }

    public function barangKeluars(): HasMany
    {
        return $this->hasMany(BarangKeluar::class);
    }

    public function eoqs(): HasMany
    {
        return $this->hasMany(Eoq::class);
    }

    public function rops(): HasMany
    {
        return $this->hasMany(Rop::class);
    }

    public function getStatusStokAttribute(): string
    {
        if ($this->stok <= 0) {
            return self::STATUS_HABIS;
        }

        if ($this->stok <= $this->stok_minimum) {
            return self::STATUS_MENIPIS;
        }

        return self::STATUS_AMAN;
    }

    public function getStatusStokLabelAttribute(): string
    {
        return match ($this->status_stok) {
            self::STATUS_HABIS => 'Habis',
            self::STATUS_MENIPIS => 'Menipis',
            default => 'Aman',
        };
    }
}
