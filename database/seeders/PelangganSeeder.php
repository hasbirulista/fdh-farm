<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PelangganSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('tb_pelanggan')->insert([
            'id'=>'1',
            'nama_pelanggan'=>'Hasbi Rulista',
            'no_hp'=>'089514687297',
            'alamat'=>'Singaparna',
            'repeat_order_aktif'=>1,
            'repeat_order_hari'=>10,
            'created_at'=>now()
        ]);
    }
}
