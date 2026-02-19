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
        Schema::create('tb_egg_grow_barang_masuk', function (Blueprint $table) {
            $table->id();
            $table->foreignId('gudang_barang_keluar_id')
                ->constrained('tb_gudang_barang_keluar') // tabel referensi
                ->cascadeOnDelete();
            $table->date('tanggal_barang_masuk');
            $table->string('jenis_telur');
            $table->integer('jumlah_barang_masuk');
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
        Schema::dropIfExists('tb_egg_grow_barang_masuk');
    }
};
