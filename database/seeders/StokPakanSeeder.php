<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StokPakanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('tb_stok_pakan')->insert([
            [
                'jenis_pakan' => 'Grower',
                'berat_total' => 0,
                'created_at'=>now()
            ],
            [
                'jenis_pakan' => 'Layer',
                'berat_total' => 0,
                'created_at'=>now()
            ]
        ]);
    }
}
