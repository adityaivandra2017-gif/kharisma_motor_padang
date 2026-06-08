<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'tanggal_masuk',
    'onderdil_id',
    'supplier_id',
    'jumlah',
    'keterangan',
])]
class BarangMasuk extends Model
{
    protected $table = 'barang_masuk';

    protected function casts(): array
    {
        return [
            'tanggal_masuk' => 'date',
        ];
    }

    public function onderdil(): BelongsTo
    {
        return $this->belongsTo(Onderdil::class);
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }
}
