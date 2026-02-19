<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('tb_produksi', function (Blueprint $table) {

            // index untuk filter tahunan / bulanan
            $table->index('tanggal_produksi');

            // index untuk filter kandang
            $table->index('nama_kandang');

            $table->index(
                ['tanggal_produksi', 'nama_kandang'],
                'idx_produksi_tanggal_kandang'
            );

            // BONUS: index kebalik (untuk query tertentu)
            $table->index(
                ['nama_kandang', 'tanggal_produksi'],
                'idx_produksi_kandang_tanggal'
            );
        });
    }

    public function down(): void
    {
        Schema::table('tb_produksi', function (Blueprint $table) {

            $table->dropIndex(['tanggal_produksi']);
            $table->dropIndex(['nama_kandang']);
            $table->dropIndex('idx_produksi_tanggal_kandang');
        });
    }
};
