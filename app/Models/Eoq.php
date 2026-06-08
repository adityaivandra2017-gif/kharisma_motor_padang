<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'onderdil_id',
    'kebutuhan_tahunan',
    'biaya_pemesanan',
    'biaya_penyimpanan',
    'hasil_eoq',
    'keterangan',
])]
class Eoq extends Model
{
    protected $table = 'eoq';

    public function onderdil(): BelongsTo
    {
        return $this->belongsTo(Onderdil::class);
    }
}
