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
            // Tambah kolom untuk support ayam apkir
            $table->string('jenis_barang')->default('telur')->after('nama_konsumen'); // 'telur' atau 'ayam_apkir'
            $table->integer('jumlah_ayam')->nullable()->after('jumlah_barang_keluar'); // untuk ayam apkir
            $table->integer('harga_ayam')->nullable()->after('harga_kilo'); // untuk ayam apkir
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tb_gudang_barang_keluar', function (Blueprint $table) {
            $table->dropColumn(['jenis_barang', 'jumlah_ayam', 'harga_ayam']);
        });
    }
};
