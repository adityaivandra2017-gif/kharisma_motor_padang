<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('barang_masuks')) {
            Schema::table('barang_masuks', function (Blueprint $table): void {
                $table->dropForeign(['onderdil_id']);
                $table->dropForeign(['supplier_id']);
            });
        }

        if (Schema::hasTable('barang_keluars')) {
            Schema::table('barang_keluars', function (Blueprint $table): void {
                $table->dropForeign(['onderdil_id']);
            });
        }

        if (Schema::hasTable('eoqs')) {
            Schema::table('eoqs', function (Blueprint $table): void {
                $table->dropForeign(['onderdil_id']);
            });
        }

        if (Schema::hasTable('onderdils')) {
            Schema::table('onderdils', function (Blueprint $table): void {
                $table->dropForeign(['supplier_id']);
            });
        }

        if (Schema::hasTable('suppliers')) {
            Schema::rename('suppliers', 'supplier');
        }

        if (Schema::hasTable('onderdils')) {
            Schema::rename('onderdils', 'onderdil');
        }

        if (Schema::hasTable('barang_masuks')) {
            Schema::rename('barang_masuks', 'barang_masuk');
        }

        if (Schema::hasTable('barang_keluars')) {
            Schema::rename('barang_keluars', 'barang_keluar');
        }

        if (Schema::hasTable('eoqs')) {
            Schema::rename('eoqs', 'eoq');
        }

        if (Schema::hasTable('onderdil')) {
            Schema::table('onderdil', function (Blueprint $table): void {
                $table->foreign('supplier_id')->references('id')->on('supplier')->cascadeOnUpdate()->restrictOnDelete();
            });
        }

        if (Schema::hasTable('barang_masuk')) {
            Schema::table('barang_masuk', function (Blueprint $table): void {
                $table->foreign('onderdil_id')->references('id')->on('onderdil')->cascadeOnUpdate()->restrictOnDelete();
                $table->foreign('supplier_id')->references('id')->on('supplier')->cascadeOnUpdate()->restrictOnDelete();
            });
        }

        if (Schema::hasTable('barang_keluar')) {
            Schema::table('barang_keluar', function (Blueprint $table): void {
                $table->foreign('onderdil_id')->references('id')->on('onderdil')->cascadeOnUpdate()->restrictOnDelete();
            });
        }

        if (Schema::hasTable('eoq')) {
            Schema::table('eoq', function (Blueprint $table): void {
                $table->foreign('onderdil_id')->references('id')->on('onderdil')->cascadeOnUpdate()->restrictOnDelete();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('barang_masuk')) {
            Schema::table('barang_masuk', function (Blueprint $table): void {
                $table->dropForeign(['onderdil_id']);
                $table->dropForeign(['supplier_id']);
            });
        }

        if (Schema::hasTable('barang_keluar')) {
            Schema::table('barang_keluar', function (Blueprint $table): void {
                $table->dropForeign(['onderdil_id']);
            });
        }

        if (Schema::hasTable('eoq')) {
            Schema::table('eoq', function (Blueprint $table): void {
                $table->dropForeign(['onderdil_id']);
            });
        }

        if (Schema::hasTable('onderdil')) {
            Schema::table('onderdil', function (Blueprint $table): void {
                $table->dropForeign(['supplier_id']);
            });
        }

        if (Schema::hasTable('supplier')) {
            Schema::rename('supplier', 'suppliers');
        }

        if (Schema::hasTable('onderdil')) {
            Schema::rename('onderdil', 'onderdils');
        }

        if (Schema::hasTable('barang_masuk')) {
            Schema::rename('barang_masuk', 'barang_masuks');
        }

        if (Schema::hasTable('barang_keluar')) {
            Schema::rename('barang_keluar', 'barang_keluars');
        }

        if (Schema::hasTable('eoq')) {
            Schema::rename('eoq', 'eoqs');
        }

        if (Schema::hasTable('onderdils')) {
            Schema::table('onderdils', function (Blueprint $table): void {
                $table->foreign('supplier_id')->references('id')->on('suppliers')->cascadeOnUpdate()->restrictOnDelete();
            });
        }

        if (Schema::hasTable('barang_masuks')) {
            Schema::table('barang_masuks', function (Blueprint $table): void {
                $table->foreign('onderdil_id')->references('id')->on('onderdils')->cascadeOnUpdate()->restrictOnDelete();
                $table->foreign('supplier_id')->references('id')->on('suppliers')->cascadeOnUpdate()->restrictOnDelete();
            });
        }

        if (Schema::hasTable('barang_keluars')) {
            Schema::table('barang_keluars', function (Blueprint $table): void {
                $table->foreign('onderdil_id')->references('id')->on('onderdils')->cascadeOnUpdate()->restrictOnDelete();
            });
        }

        if (Schema::hasTable('eoqs')) {
            Schema::table('eoqs', function (Blueprint $table): void {
                $table->foreign('onderdil_id')->references('id')->on('onderdils')->cascadeOnUpdate()->restrictOnDelete();
            });
        }
    }
};
