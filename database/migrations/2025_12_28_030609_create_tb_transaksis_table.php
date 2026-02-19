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
        Schema::create('tb_transaksi', function (Blueprint $table) {
            $table->id();

            $table->foreignId('pelanggan_id')
                ->constrained('tb_pelanggan')
                ->onDelete('cascade');

            $table->date('tanggal_transaksi');
            $table->string('jenis_telur');
            
            $table->integer('total_berat');
            $table->integer('harga_beli_kilo');
            $table->integer('harga_jual_kilo');
            $table->integer('total_harga');
            $table->string('pembayaran');
            $table->timestamps();

            $table->index('pelanggan_id');
            $table->index('tanggal_transaksi');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_transaksi');
    }
};
