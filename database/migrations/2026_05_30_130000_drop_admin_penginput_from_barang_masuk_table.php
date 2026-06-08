<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('barang_masuk', 'admin_penginput')) {
            Schema::table('barang_masuk', function (Blueprint $table): void {
                $table->dropColumn('admin_penginput');
            });
        }
    }

    public function down(): void
    {
        Schema::table('barang_masuk', function (Blueprint $table): void {
            $table->string('admin_penginput', 100)->nullable()->after('keterangan');
        });
    }
};
