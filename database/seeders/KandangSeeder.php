<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

use function Symfony\Component\Clock\now;

class KandangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {


        DB::table('tb_kandang')->insert([
            [
                'nama_kandang' => 'Kandang 1',
                'chicken_in' => 'Kamis',
                'populasi_ayam' => 590,
                'created_at'=>now()
            ],
            [
                'nama_kandang' => 'Kandang 2',
                'chicken_in' => 'Jumat',
                'populasi_ayam' => 911,
                'created_at'=>now()
            ],
            [
                'nama_kandang' => 'Kandang 3',
                'chicken_in' => 'Sabtu',
                'populasi_ayam' => 495,
                'created_at'=>now()
            ],
            [
                'nama_kandang' => 'Kandang 4',
                'chicken_in' => 'Minggu',
                'populasi_ayam' => 574,
                'created_at'=>now()
            ]
        ]);
    }
}
