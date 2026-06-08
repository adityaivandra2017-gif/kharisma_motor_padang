<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['nama_supplier', 'kontak', 'alamat'])]
class Supplier extends Model
{
    protected $table = 'supplier';

    public function onderdils(): HasMany
    {
        return $this->hasMany(Onderdil::class);
    }

    public function barangMasuks(): HasMany
    {
        return $this->hasMany(BarangMasuk::class);
    }
}
