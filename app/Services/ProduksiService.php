<?php

namespace App\Services;

use App\Models\Kandang;
use App\Models\Produksi;
use App\Models\StokPakan;
use App\Models\KandangPakan;
use App\Models\StokTelur;
use Illuminate\Support\Facades\DB;

class ProduksiService
{
    public function simpanProduksi(array $data): void
    {
        DB::transaction(function () use ($data) {

            $kandang = Kandang::lockForUpdate()->findOrFail($data['kandang_id']);

            $stokPakan = StokPakan::where('jenis_pakan', 'Layer')
                ->lockForUpdate()
                ->firstOrFail();

            $kandangPakan = KandangPakan::where('kandang_id', $kandang->id)
                ->where('stok_pakan_id', $stokPakan->id)
                ->lockForUpdate()
                ->firstOrFail();

            if ($kandangPakan->stok < $data['pakan_B']) {
                throw new \Exception('Stok pakan layer tidak mencukupi');
            }

            $kandangPakan->decrement('stok', $data['pakan_B']);

            $produksi = Produksi::create([
                'tanggal_produksi' => $data['tanggal_produksi'],
                'nama_kandang' => $kandang->nama_kandang,
                'populasi_ayam' => $data['populasi_ayam'],
                'usia' => $data['usia'],
                'jenis_telur' => $data['jenis_telur'],
                'mati' => 0,
                'apkir' => 0,
                'jumlah_butir' => $data['jumlah_butir'],
                'jumlah_pecah' => 0,
                'jumlah_gram' => $data['jumlah_gram'],
                'grower_per_ayam' => 0,
                'layer_per_ayam' => 125,
                'pakan_A' => 0,
                'pakan_B' => $data['pakan_B'],
                'persentase_produksi' => ($data['jumlah_butir'] / $data['populasi_ayam']) * 100,
                'kegiatan' => 'Produksi Harian',
                'keterangan' => 'Produksi otomatis (Seeder)',
            ]);

            $produksi->gudangMasuk()->create([
                'tanggal_barang_masuk' => $data['tanggal_produksi'],
                'nama_kandang' => $kandang->nama_kandang,
                'jenis_telur' => $data['jenis_telur'],
                'jumlah_butir' => $data['jumlah_butir'],
                'jumlah_pecah' => 0,
                'jumlah_gram' => $data['jumlah_gram'],
            ]);

            // UPDATE atau TAMBAH stok telur
            StokTelur::updateOrCreate(
                [
                    'jenis_stok' => 'gudang',
                    'jenis_telur' => $data['jenis_telur']
                ],
                [
                    'total_stok' => DB::raw('COALESCE(total_stok, 0) + ' . $data['jumlah_gram'])
                ]
            );
        });
    }
}
