<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StokTelurSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('tb_stok_telur')->insert([
            [
                'jenis_stok' => 'gudang',
                'total_stok' => 0,
                'jenis_telur' => 'Omega',
                'created_at'=>now()
            ],
            [
                'jenis_stok' => 'gudang',
                'total_stok' => 0,
                'jenis_telur' => 'Biasa',
                'created_at'=>now()
            ],
            [
                'jenis_stok' => 'toko',
                'total_stok' => 0,
                'jenis_telur' => 'Omega',
                'created_at'=>now()
            ],
            [
                'jenis_stok' => 'toko',
                'total_stok' => 0,
                'jenis_telur' => 'Biasa',
                'created_at'=>now()
            ]
        ]);
    }
}
