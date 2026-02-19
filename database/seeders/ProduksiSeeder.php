<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class ProduksiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('tb_produksi')->insert([
            'tanggal_produksi'=>fake()->dateTimeBetween('-1 year', 'now'),
            'nama_kandang'=>'Kandang 1',
            'populasi_ayam'=>923,
            'usia'=>21,
            'mati'=>1,
            'pakan_A'=>63529,
            'pakan_B'=>50000,
            'jumlah_butir'=>100,
            'jumlah_pecah'=>0,
            'jumlah_gram'=>4500,
            'persentase_produksi'=>10.3,
            'keterangan'=>'deskripsi keterangan',
            'created_at'=>now()
        ]);
    }
}
