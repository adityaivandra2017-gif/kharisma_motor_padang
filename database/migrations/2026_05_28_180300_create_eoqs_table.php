<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('eoqs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('onderdil_id')->constrained('onderdils')->cascadeOnUpdate()->restrictOnDelete();
            $table->unsignedInteger('kebutuhan_tahunan');
            $table->unsignedInteger('biaya_pemesanan');
            $table->unsignedInteger('biaya_penyimpanan');
            $table->unsignedInteger('hasil_eoq');
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('eoqs');
    }
};
