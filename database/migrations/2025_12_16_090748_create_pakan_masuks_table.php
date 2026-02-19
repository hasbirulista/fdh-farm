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
        Schema::create('tb_pakan_masuk', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal_pakan_masuk');
            $table->string('jenis_pakan');
            $table->integer('berat_total');
            $table->integer('harga_kilo');
            $table->integer('total_harga');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_pakan_masuk');
    }
};
