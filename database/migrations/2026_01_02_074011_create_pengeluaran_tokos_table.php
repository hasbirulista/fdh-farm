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
        Schema::create('tb_pengeluaran_toko', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal');
            $table->enum('jenis_pengeluaran', ['telur pecah', 'lainnya']);

            // khusus pakan
            $table->string('jenis_telur')->nullable();
            $table->integer('berat_total')->nullable(); // gram
            
            // khusus lainnya
            $table->string('nama_pengeluaran')->nullable();
            $table->integer('nominal');

            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_pengeluaran_toko');
    }
};
