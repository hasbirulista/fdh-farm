<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $this->call([
            // =========================
            // AUTH & MASTER
            // =========================
            OwnerSeeder::class,
            PelangganSeeder::class,

            // =========================
            // KANDANG & STOK AWAL
            // =========================
            KandangSeeder::class,
            StokPakanSeeder::class,
            KandangPakanSeeder::class,

            // =========================
            // PRODUKSI (BUTUH KANDANG + PAKAN)
            // =========================
            // ProduksiBulananSeeder::class,

            // =========================
            // STOK & SALDO SETELAH PRODUKSI
            // =========================
            StokTelurSeeder::class,
            SaldoSeeder::class,

            // =========================
            // TRANSAKSI LAIN
            // =========================
            // PengeluaranSeeder::class,
            // GudangBarangKeluarSeeder::class,
        ]);
    }
}
