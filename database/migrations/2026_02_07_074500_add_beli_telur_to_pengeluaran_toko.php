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
        Schema::table('tb_pengeluaran_toko', function (Blueprint $table) {
            // Update enum untuk menambahkan 'beli telur'
            $table->enum('jenis_pengeluaran', ['telur pecah', 'lainnya', 'beli telur'])
                ->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tb_pengeluaran_toko', function (Blueprint $table) {
            // Rollback ke enum original (hanya 2 nilai)
            $table->enum('jenis_pengeluaran', ['telur pecah', 'lainnya'])
                ->change();
        });
    }
};
