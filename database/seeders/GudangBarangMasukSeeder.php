<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GudangBarangMasukSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('tb_gudang_barang_masuk')->insert([
            'produksi_id'=>'1',
            'tanggal_barang_masuk'=>fake()->dateTimeBetween('-1 year', 'now'),
            'nama_kandang'=>'Kandang 1',
            'jumlah_butir'=>100,
            'jumlah_pecah'=>0,
            'jumlah_gram'=>4500,
            'created_at'=>now()
        ]);
    }
}
