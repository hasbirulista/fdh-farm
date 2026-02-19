<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KandangPakanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('tb_kandang_pakan')->insert([
            [
                'kandang_id' => 1,
                'stok_pakan_id' => 1,
                'stok' => 500000,
                'created_at'=>now()
            ],
            [
                'kandang_id' => 1,
                'stok_pakan_id' => 2,
                'stok' => 500000,
                'created_at'=>now()
            ],
            [
                'kandang_id' => 2,
                'stok_pakan_id' => 1,
                'stok' => 500000,
                'created_at'=>now()
            ],
            [
                'kandang_id' => 2,
                'stok_pakan_id' => 2,
                'stok' => 500000,
                'created_at'=>now()
            ],
            [
                'kandang_id' => 3,
                'stok_pakan_id' => 1,
                'stok' => 500000,
                'created_at'=>now()
            ],
            [
                'kandang_id' => 3,
                'stok_pakan_id' => 2,
                'stok' => 500000,
                'created_at'=>now()
            ],
            [
                'kandang_id' => 4,
                'stok_pakan_id' => 1,
                'stok' => 500000,
                'created_at'=>now()
            ],
            [
                'kandang_id' => 4,
                'stok_pakan_id' => 2,
                'stok' => 500000,
                'created_at'=>now()
            ],
            
        ]);
    }
}
