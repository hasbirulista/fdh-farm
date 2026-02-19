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
        Schema::create('tb_gudang_barang_keluar', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal_barang_keluar');
            $table->string('nama_konsumen');
            $table->string('jenis_telur');
            $table->integer('jumlah_barang_keluar');
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
        Schema::dropIfExists('tb_gudang_barang_keluar');
    }
};
