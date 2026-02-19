<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Http\Controllers\GudangController;

class GudangBarangKeluarSeeder extends Seeder
{
    public function run(): void
    {
        $controller = new GudangController();

        $start = Carbon::create(2025, 1, 1);
        $end   = Carbon::create(2025, 12, 31);

        $jenisTelur = ['Omega', 'Biasa'];

        for ($date = $start->copy(); $date <= $end; $date->addDay()) {

            // =========================
            // DATA REALISTIS
            // =========================
            $telur = $jenisTelur[array_rand($jenisTelur)];
            $jumlahGram = rand(5000, 20000); // 5kg - 20kg
            $hargaKilo = rand(25000, 27000);

            $totalHarga = ($jumlahGram / 1000) * $hargaKilo;

            // =========================
            // SIMULASI REQUEST (SAMA PERSIS SEPERTI FORM)
            // =========================
            $request = Request::create('/dashboard/gudang/barang-keluar', 'POST', [
                'tanggal_barang_keluar' => $date->toDateString(),
                'nama_konsumen'         => 'Egg Grow',
                'jenis_telur'           => $telur,
                'jumlah_barang_keluar'  => $jumlahGram,
                'harga_kilo'            => $hargaKilo,
                'total_harga'           => round($totalHarga),
            ]);

            try {
                $controller->storeBarangKeluar($request);
            } catch (\Exception $e) {
                // skip jika stok/saldo habis
                continue;
            }
        }
    }
}
