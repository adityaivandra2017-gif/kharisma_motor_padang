<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'tanggal_keluar',
    'onderdil_id',
    'jumlah',
    'keterangan',
])]
class BarangKeluar extends Model
{
    protected $table = 'barang_keluar';

    protected function casts(): array
    {
        return [
            'tanggal_keluar' => 'date',
        ];
    }

    public function onderdil(): BelongsTo
    {
        return $this->belongsTo(Onderdil::class);
    }
}
