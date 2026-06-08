<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rop', function (Blueprint $table) {
            $table->id();
            $table->foreignId('onderdil_id')->constrained('onderdil')->cascadeOnUpdate()->restrictOnDelete();
            $table->unsignedInteger('lead_time');
            $table->unsignedInteger('kebutuhan_per_hari');
            $table->unsignedInteger('safety_stock');
            $table->unsignedInteger('hasil_rop');
            $table->string('status_stok', 30);
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rop');
    }
};
