<?php

namespace Database\Seeders;

use App\Models\Supplier;
use Illuminate\Database\Seeder;

class SupplierSeeder extends Seeder
{
    public function run(): void
    {
        $suppliers = [
            ['nama_supplier' => 'PT Sumber Sparepart Jaya', 'kontak' => '0812-1000-0001'],
            ['nama_supplier' => 'CV Mitra Onderdil Motor', 'kontak' => '0813-2000-0002'],
            ['nama_supplier' => 'UD Berkah Racing Parts', 'kontak' => '0812-3000-0003'],
        ];

        foreach ($suppliers as $supplier) {
            Supplier::firstOrCreate(
                ['nama_supplier' => $supplier['nama_supplier']],
                $supplier
            );
        }
    }
}
