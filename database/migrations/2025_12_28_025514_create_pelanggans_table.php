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
        Schema::create('tb_pelanggan', function (Blueprint $table) {
            $table->id();
            $table->string('nama_pelanggan');
            $table->string('no_hp');
            $table->string('alamat');
            $table->boolean('repeat_order_aktif')->default(false);
            $table->integer('repeat_order_hari')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_pelanggan');
        
    }
};
