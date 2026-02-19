<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BarangMasukTokoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('tb_egg_grow_barang_masuk')->insert([
            'id'=>'1',
            'gudang_barang_keluar_id'=>'1',
            'tanggal_barang_masuk'=>fake()->dateTimeBetween('-1 year', 'now'),
            'jenis_telur'=>'Biasa',
            'jumlah_barang_masuk'=>50000,
            'harga_kilo'=>25000,
            'total_harga'=>1250000,
            'created_at'=>now()
        ]);
    }
}
