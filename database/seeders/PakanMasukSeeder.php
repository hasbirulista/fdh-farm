<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PakanMasukSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('tb_pakan_masuk')->insert([
            [
                'tanggal_pakan_masuk' => fake()->dateTimeBetween('-1 year', 'now'),
                'jenis_pakan' => 'Pakan A',
                'berat_total' => 1000000,
                'harga_kilo' => 7400,
                'total_harga' => 7400000,
                'created_at' => now()
            ],
            [
                'tanggal_pakan_masuk' => fake()->dateTimeBetween('-1 year', 'now'),
                'jenis_pakan' => 'Pakan B',
                'berat_total' => 1000000,
                'harga_kilo' => 7600,
                'total_harga' => 7600000,
                'created_at' => now()
            ]
        ]);
    }
}
