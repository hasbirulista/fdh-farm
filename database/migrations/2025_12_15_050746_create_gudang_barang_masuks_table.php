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
        Schema::create('tb_gudang_barang_masuk', function (Blueprint $table) {
            $table->id();
            $table->foreignId('produksi_id')
                ->constrained('tb_produksi')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->date('tanggal_barang_masuk');
            $table->string('nama_kandang');
            $table->string('jenis_telur');
            $table->integer('jumlah_butir');
            $table->integer('jumlah_pecah');
            $table->integer('jumlah_gram');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_gudang_barang_masuk');
    }
};
