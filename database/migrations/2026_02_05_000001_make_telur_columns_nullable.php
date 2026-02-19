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
        Schema::table('tb_gudang_barang_keluar', function (Blueprint $table) {
            // Ubah kolom telur menjadi nullable
            $table->string('jenis_telur')->nullable()->change();
            $table->integer('jumlah_barang_keluar')->nullable()->change();
            $table->integer('harga_kilo')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tb_gudang_barang_keluar', function (Blueprint $table) {
            // Kembalikan ke nullable false (jika diperlukan rollback)
            $table->string('jenis_telur')->nullable(false)->change();
            $table->integer('jumlah_barang_keluar')->nullable(false)->change();
            $table->integer('harga_kilo')->nullable(false)->change();
        });
    }
};
