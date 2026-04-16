<?php

namespace App\Http\Controllers;

use App\Models\Kandang;
use App\Models\Produksi;
use App\Models\StokTelur;
use App\Models\StokPakan;
use App\Models\Saldo;
use App\Models\Transaksi;
use App\Models\Pengeluaran;
use App\Models\PengeluaranToko;
use App\Models\GudangBarangKeluar;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class DashboardOwnerController extends Controller
{
    public function index()
    {

        // ================= FILTER =================
        $bulan = request('bulan', now()->month);
        $tahun = request('tahun', now()->year);
        $hariIni = now()->toDateString();

        /* ========================== DASHBOARD KANDANG ========================== */

        // Produksi hari ini (%)
        $produksiHariIni = Produksi::whereDate('tanggal_produksi', $hariIni)
            ->avg('persentase_produksi');

        // Rata-rata produksi bulan terpilih (%)
        $rataRataBulanIni = Produksi::whereMonth('tanggal_produksi', $bulan)
            ->whereYear('tanggal_produksi', $tahun)
            ->avg('persentase_produksi');

        // Telur hari ini
        $telurHariIni = Produksi::whereDate('tanggal_produksi', $hariIni)
            ->selectRaw('SUM(jumlah_butir) as total_butir, SUM(jumlah_gram) as total_gram')
            ->first();

        // Populasi ayam
        $totalPopulasi = Kandang::sum('populasi_ayam');
        $jumlahKandang = Kandang::count();

        /* ========================== GRAFIK PRODUKSI ========================== */

        // 🔹 Produksi Harian (berdasarkan filter bulan & tahun)
        $produksiHarianFilter = Produksi::select(
            DB::raw('DAY(tanggal_produksi) as hari'),
            DB::raw('AVG(persentase_produksi) as produksi')
        )
            ->whereMonth('tanggal_produksi', $bulan)
            ->whereYear('tanggal_produksi', $tahun)
            ->groupBy('hari')
            ->orderBy('hari')
            ->get();

        $labelProduksiHarianFilter = $produksiHarianFilter->pluck('hari');
        $dataProduksiHarianFilter = $produksiHarianFilter->pluck('produksi');

        // 🔹 Produksi Bulanan (global %)
        $produksiBulananGlobal = Produksi::select(
            DB::raw('MONTH(tanggal_produksi) as bulan'),
            DB::raw('AVG(persentase_produksi) as produksi')
        )
            ->whereYear('tanggal_produksi', $tahun)
            ->groupBy('bulan')
            ->orderBy('bulan')
            ->get();

        $labelProduksiBulananGlobal = $produksiBulananGlobal->pluck('bulan')
            ->map(fn($b) => \Carbon\Carbon::create()->month($b)->translatedFormat('F'));

        $dataProduksiBulananGlobal = $produksiBulananGlobal->pluck('produksi');

        // 🔹 Produksi Per Kandang Harian
        $produksiKandangHarian = Produksi::select(
            'nama_kandang',
            DB::raw('DATE(tanggal_produksi) as tanggal'),
            DB::raw('AVG(persentase_produksi) as produksi')
        )
            ->whereMonth('tanggal_produksi', $bulan)
            ->whereYear('tanggal_produksi', $tahun)
            ->groupBy('nama_kandang', 'tanggal')
            ->orderBy('tanggal')
            ->get()
            ->groupBy('nama_kandang');

        // Ambil semua tanggal unik dari data produksi yang ada (urutan naik)
        $labelProduksiKandangHarian = $produksiKandangHarian->mapWithKeys(function ($items, $kandang) {
            return [$kandang => $items->pluck('tanggal')->map(fn($tgl) => \Carbon\Carbon::parse($tgl)->format('d'))];
        });

        // Siapkan data chart per kandang
        $dataProduksiKandangHarian = [];
        foreach ($produksiKandangHarian as $namaKandang => $records) {
            $dataProduksiKandangHarian[$namaKandang] = $labelProduksiKandangHarian->map(function ($tanggal) use ($records) {
                $found = $records->firstWhere('tanggal', $tanggal);
                return $found ? $found->produksi : 0; // 0 jika tidak ada data
            });
        }

        // 🔹 Produksi Per Kandang Bulanan
        // 🔹 Produksi Per Kandang Bulanan (NAMA BULAN)
        $produksiKandangBulanan = Produksi::select(
            'nama_kandang',
            DB::raw('MONTH(tanggal_produksi) as bulan'),
            DB::raw('AVG(persentase_produksi) as produksi')
        )
            ->whereYear('tanggal_produksi', $tahun)
            ->groupBy('nama_kandang', 'bulan')
            ->orderBy('nama_kandang')
            ->orderBy('bulan')
            ->get()
            ->map(function ($item) {
                return [
                    'nama_kandang' => $item->nama_kandang,
                    'bulan' => \Carbon\Carbon::create()->month($item->bulan)->translatedFormat('F'),
                    'produksi' => $item->produksi,
                ];
            })
            ->groupBy('nama_kandang');


        /* ========================== DASHBOARD GUDANG ========================== */

        $saldoGudang = Saldo::where('jenis_saldo', 'gudang')->value('jumlah_saldo') ?? 0;

        $stokGudangOmegaKg = (StokTelur::where('jenis_stok', 'gudang')
            ->where('jenis_telur', 'Omega')
            ->value('total_stok') ?? 0) / 1000;

        $stokGudangBiasaKg = (StokTelur::where('jenis_stok', 'gudang')
            ->where('jenis_telur', 'Biasa')
            ->value('total_stok') ?? 0) / 1000;

        $pengeluaranGudangBulanIni = Pengeluaran::whereMonth('tanggal', $bulan)
            ->whereYear('tanggal', $tahun)
            ->sum('total_harga');

        $pemasukkanGudangBulanIni = GudangBarangKeluar::whereMonth('tanggal_barang_keluar', $bulan)
            ->whereYear('tanggal_barang_keluar', $tahun)
            ->sum('total_harga');

        $hargaTelurHariIni = GudangBarangKeluar::whereDate('tanggal_barang_keluar', $hariIni)
            ->where('jenis_barang', 'telur')
            ->avg('harga_kilo');

        /* ========================== DASHBOARD TOKO ========================== */

        /* ========================== DASHBOARD TOKO ========================== */

        $saldoTokoCash = Saldo::where('jenis_saldo', 'toko')->value('jumlah_saldo') ?? 0;

        // 🔹 Saldo Kredit Toko (Piutang pelanggan dengan pembayaran Kredit)
        $saldoTokoCreditValue = Transaksi::where('pembayaran', 'Kredit')
            ->sum('total_harga') ?? 0;

        // 🔹 Stok Telur Toko
        $stokTelurOmegaToko = (StokTelur::where('jenis_stok', 'toko')
            ->where('jenis_telur', 'Omega')
            ->value('total_stok') ?? 0) / 1000;

        $stokTelurBiasaToko = (StokTelur::where('jenis_stok', 'toko')
            ->where('jenis_telur', 'Biasa')
            ->value('total_stok') ?? 0) / 1000;

        // 🔹 Harga Rata-rata Telur Toko Hari Ini
        $hargaTelurOmegaToko = Transaksi::whereDate('tanggal_transaksi', $hariIni)
            ->where('jenis_telur', 'Omega')
            ->avg('harga_jual_kilo') ?? 0;

        $hargaTelurBiasaToko = Transaksi::whereDate('tanggal_transaksi', $hariIni)
            ->where('jenis_telur', 'Biasa')
            ->avg('harga_jual_kilo') ?? 0;

        // 🔹 Total Saldo Toko = Cash + Credit + Nilai Stok
        $saldoTelurOmega = $stokTelurOmegaToko * $hargaTelurOmegaToko;
        $saldoTelurBiasa = $stokTelurBiasaToko * $hargaTelurBiasaToko;
        $saldoToko = $saldoTokoCash + $saldoTokoCreditValue + $saldoTelurOmega + $saldoTelurBiasa;
        
        // 🔹 Penjualan bulan terpilih (FIXED)
        $penjualanTokoBulanIni = Transaksi::whereMonth('tanggal_transaksi', $bulan)
            ->whereYear('tanggal_transaksi', $tahun)
            ->sum('total_harga');

        // 🔹 Penjualan Harian (untuk chart)
        $penjualanHarian = Transaksi::select(
            DB::raw('DATE(tanggal_transaksi) as tanggal'),
            DB::raw('SUM(total_harga) as total')
        )
            ->whereMonth('tanggal_transaksi', $bulan)
            ->whereYear('tanggal_transaksi', $tahun)
            ->groupBy('tanggal')
            ->orderBy('tanggal')
            ->get();

        $labelPenjualanHarian = $penjualanHarian->pluck('tanggal')
            ->map(fn($tgl) => \Carbon\Carbon::parse($tgl)->format('j'));
        $dataPenjualanHarian = $penjualanHarian->pluck('total');

        // 🔹 Penjualan Bulanan (untuk chart tahunan)
        $penjualanBulanan = Transaksi::select(
            DB::raw('MONTH(tanggal_transaksi) as bulan'),
            DB::raw('SUM(total_harga) as total')
        )
            ->whereYear('tanggal_transaksi', $tahun)
            ->groupBy('bulan')
            ->orderBy('bulan')
            ->get();

        $labelPenjualanBulanan = $penjualanBulanan->pluck('bulan')
            ->map(fn($b) => \Carbon\Carbon::create()->month($b)->translatedFormat('F'));

        $dataPenjualanBulanan = $penjualanBulanan->pluck('total');

        $pengeluaranTokoBulanIni = PengeluaranToko::whereMonth('tanggal', $bulan)
            ->whereYear('tanggal', $tahun)
            ->sum('nominal');
        
        // 🔹 Profit dari transaksi TUNAI/TRANSFER (dihitung pada tanggal_transaksi)
        $profitTanggalTransaksi = Transaksi::whereMonth('tanggal_transaksi', $bulan)
            ->whereYear('tanggal_transaksi', $tahun)
            ->whereIn('pembayaran', ['Tunai', 'Transfer'])
            ->whereNull('tanggal_pelunasan')  // Exclude kredit yang sudah dilunaskan
            ->selectRaw('SUM(total_harga - (harga_beli_kilo * total_berat / 1000)) as profit')
            ->value('profit') ?? 0;

        // 🔹 Profit dari transaksi KREDIT yang SUDAH LUNAS (dihitung pada tanggal_pelunasan)
        $profitTanggalPelunasan = Transaksi::whereMonth('tanggal_pelunasan', $bulan)
            ->whereYear('tanggal_pelunasan', $tahun)
            ->where('status_pelunasan', 'lunas')
            ->selectRaw('SUM(total_harga - (harga_beli_kilo * total_berat / 1000)) as profit')
            ->value('profit') ?? 0;

        $profitBulanIni = $profitTanggalTransaksi + $profitTanggalPelunasan;
        $labaToko = $profitBulanIni;

        /* ========================== RETURN VIEW ========================== */

        return view('dashboard', compact(

            'bulan',
            'tahun',

            // SUMMARY
            'produksiHariIni',
            'rataRataBulanIni',
            'telurHariIni',
            'totalPopulasi',
            'jumlahKandang',

            // PRODUKSI
            'labelProduksiHarianFilter',
            'dataProduksiHarianFilter',
            'labelProduksiBulananGlobal',
            'dataProduksiBulananGlobal',
            'produksiKandangHarian',
            'produksiKandangBulanan',

            'labelProduksiKandangHarian',  // << tambahkan
            'dataProduksiKandangHarian',   // << tambahkan

            // GUDANG
            'saldoGudang',
            'stokGudangOmegaKg',
            'stokGudangBiasaKg',
            'pengeluaranGudangBulanIni',
            'pemasukkanGudangBulanIni',
            'hargaTelurHariIni',

            // TOKO
            'saldoToko',
            'saldoTokoCash',
            'saldoTokoCreditValue',
            'stokTelurOmegaToko',
            'stokTelurBiasaToko',
            'hargaTelurOmegaToko',
            'hargaTelurBiasaToko',
            'saldoTelurOmega',
            'saldoTelurBiasa',
            'penjualanTokoBulanIni',
            'pengeluaranTokoBulanIni',
            'labaToko',
            'labelPenjualanHarian',
            'dataPenjualanHarian',
            'labelPenjualanBulanan',
            'dataPenjualanBulanan'
        ), [
            'page' => 'Dashboard'
        ]);
    }

    public function updateSaldoGudang(Request $request)
    {
        $request->validate([
            'saldo' => 'required|numeric|min:0'
        ]);

        Saldo::where('jenis_saldo', 'gudang')
            ->update(['jumlah_saldo' => $request->saldo]);

        return redirect()
            ->route('dashboard')
            ->with('success', 'Saldo Gudang Berhasil Diperbarui');
    }

    public function updateSaldoToko(Request $request)
    {
        $request->validate([
            'saldo' => 'required|numeric|min:0'
        ]);

        Saldo::where('jenis_saldo', 'toko')
            ->update(['jumlah_saldo' => $request->saldo]);

        return redirect()
            ->route('dashboard')
            ->with('success', 'Saldo Toko Berhasil Diperbarui');
    }
}
