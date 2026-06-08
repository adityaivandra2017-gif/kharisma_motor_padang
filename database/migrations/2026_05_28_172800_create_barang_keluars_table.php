<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('barang_keluars', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal_keluar');
            $table->foreignId('onderdil_id')->constrained('onderdils')->cascadeOnUpdate()->restrictOnDelete();
            $table->unsignedInteger('jumlah');
            $table->text('keterangan')->nullable();
            $table->timestamps();

            $table->index('tanggal_keluar');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('barang_keluars');
    }
};
