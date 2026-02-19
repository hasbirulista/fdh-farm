<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tb_distribusi_pakan', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal_distribusi');
            $table->foreignId('kandang_id')->constrained('tb_kandang')->cascadeOnDelete();
            $table->foreignId('stok_pakan_id')->constrained('tb_stok_pakan')->cascadeOnDelete();
            $table->integer('jumlah_berat');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_distribusi_pakan');
    }
};
