<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SaldoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('tb_saldo')->insert([
            [
                'jenis_saldo' => 'gudang',
                'jumlah_saldo' => 50000000,
                'created_at' => now()
            ],
            [
                'jenis_stok' => 'toko',
                'jumlah_saldo' => 50000000,
                'created_at' => now()
            ]
        ]);
    }
}
