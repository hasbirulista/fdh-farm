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

        Schema::create('tb_produksi', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal_produksi');
            $table->string('nama_kandang');
            $table->integer('populasi_ayam');
            $table->integer('usia');
            $table->integer('mati');
            $table->integer('apkir');
            $table->integer('grower_per_ayam');
            $table->integer('layer_per_ayam');
            $table->integer('pakan_A');
            $table->integer('pakan_B');
            $table->string('jenis_telur');
            $table->integer('jumlah_butir');
            $table->integer('jumlah_pecah');
            $table->integer('jumlah_gram');
            $table->decimal('persentase_produksi');
            $table->text('kegiatan');
            $table->text('keterangan');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_produksi');
    }
};
