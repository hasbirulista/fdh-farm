<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Carbon\Carbon;
use Faker\Factory as Faker;
use App\Models\Kandang;
use App\Services\ProduksiService;

class ProduksiBulananSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('id_ID');
        $service = new ProduksiService();

        // Range tanggal (tahun 2025)
        $start = Carbon::create(2025, 1, 1);
        $end   = Carbon::create(2025, 12, 31);

        $kandangs = Kandang::all();

        foreach ($kandangs as $kandang) {

            $tanggal = $start->copy();
            $usia = 15; // usia awal produksi

            while ($tanggal <= $end) {

                $populasi = $kandang->populasi_ayam;
                if ($populasi <= 0) {
                    break; // jika kandang kosong → stop
                }

                // Usia naik tiap 7 hari
                if ($tanggal->diffInDays($start) > 0 && $tanggal->diffInDays($start) % 7 === 0) {
                    $usia++;
                }

                $jumlahButir = $faker->numberBetween(500, 600);
                $jenisTelur = $faker->randomElement(['Omega', 'Biasa']);
                $jumlahGram = $jumlahButir * 60;
                $pakanB = $populasi * 125;

                $data = [
                    'tanggal_produksi' => $tanggal->toDateString(),
                    'kandang_id'       => $kandang->id,
                    'populasi_ayam'    => $populasi,
                    'usia'             => $usia,
                    'jenis_telur'      => $jenisTelur,
                    'jumlah_butir'     => $jumlahButir,
                    'jumlah_gram'      => $jumlahGram,
                    'pakan_B'          => $pakanB,
                ];

                try {
                    // Simpan produksi & update stok telur otomatis
                    $service->simpanProduksi($data);
                } catch (\Throwable $e) {
                    // bisa terjadi: stok pakan tidak cukup → skip hari ini
                    continue;
                }

                $tanggal->addDay();
            }
        }
    }
}
