<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PengeluaranSeeder extends Seeder
{
    public function run(): void
    {
        $start = Carbon::create(2025, 1, 1);
        $end   = Carbon::create(2025, 12, 31);

        $jenisPakan = ['Grower', 'Layer'];
        $namaLainnya = [
            'Listrik',
            'Air',
            'Obat Ayam',
            'Vitamin',
            'Perbaikan Kandang',
            'Transport',
            'Alat Kandang'
        ];

        DB::transaction(function () use ($start, $end, $jenisPakan, $namaLainnya) {

            for ($date = $start->copy(); $date <= $end; $date->addDay()) {

                // 👉 probabilitas: 70% pakan, 30% lainnya
                $isPakan = rand(1, 100) <= 70;

                if ($isPakan) {
                    // ================= PENGELUARAN PAKAN =================
                    $beratKg = rand(50, 200); // kilo
                    $hargaKilo = rand(7000, 9000);
                    $totalHarga = $beratKg * $hargaKilo;

                    DB::table('tb_pengeluaran')->insert([
                        'tanggal'          => $date->toDateString(),
                        'jenis_pengeluaran' => 'pakan',
                        'jenis_pakan'      => $jenisPakan[array_rand($jenisPakan)],
                        'berat_total'      => $beratKg * 1000, // simpan gram
                        'harga_kilo'       => $hargaKilo,
                        'total_harga'      => $totalHarga,
                        'nama_pengeluaran' => null,
                        'keterangan'       => 'Pembelian pakan harian',
                        'created_at'       => now(),
                        'updated_at'       => now(),
                    ]);
                } else {
                    // ================= PENGELUARAN LAINNYA =================
                    $nominal = rand(50_000, 500_000);

                    DB::table('tb_pengeluaran')->insert([
                        'tanggal'          => $date->toDateString(),
                        'jenis_pengeluaran' => 'lainnya',
                        'jenis_pakan'      => null,
                        'berat_total'      => null,
                        'harga_kilo'       => null,
                        'total_harga'      => $nominal,
                        'nama_pengeluaran' => $namaLainnya[array_rand($namaLainnya)],
                        'keterangan'       => 'Pengeluaran operasional',
                        'created_at'       => now(),
                        'updated_at'       => now(),
                    ]);
                }
            }
        });
    }
}
