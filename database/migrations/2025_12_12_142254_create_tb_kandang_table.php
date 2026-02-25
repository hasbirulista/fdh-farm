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
        Schema::create('tb_kandang', function (Blueprint $table) {
            $table->id();
            $table->string('nama_kandang');
            $table->string('chicken_in');
            $table->integer('populasi_ayam');
            $table->string('anak_kandang')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_kandang');
    }
};
