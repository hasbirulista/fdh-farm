<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kandang;
use App\Models\KandangPakan;
use App\Models\StokPakan;
use App\Models\PakanMasuk;
use App\Models\Produksi;
use App\Models\StokTelur;
use App\Models\Saldo;
use App\Models\GudangBarangMasuk;
use App\Models\GudangBarangKeluar;
use App\Models\EggGrowBarangMasuk;
use App\Models\Pengeluaran;
use App\Models\PengeluaranToko;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class GudangController extends Controller
{
    public function gudang()
    {
        $saldoGudang = Saldo::where('jenis_saldo', 'gudang')->first();

        // Ambil stok telur gudang
        $stokTelurOmegaGudang = StokTelur::where('jenis_stok', 'gudang')
            ->where('jenis_telur', 'Omega')
            ->first();

        $stokTelurBiasaGudang = StokTelur::where('jenis_stok', 'gudang')
            ->where('jenis_telur', 'Biasa')
            ->first();

        // Ambil stok pakan
        $stokPakanGrower = StokPakan::where('jenis_pakan', 'Grower')->first();
        $stokPakanLayer = StokPakan::where('jenis_pakan', 'Layer')->first();

        // ===============================
        // PAKAN → KG
        // ===============================
        $growerKg = $stokPakanGrower ? $stokPakanGrower->berat_total / 1000 : 0;
        $layerKg  = $stokPakanLayer  ? $stokPakanLayer->berat_total / 1000 : 0;

        // ===============================
        // TELUR → AMBIL GRAM ASLI
        // ===============================
        $stokTelurOmegaGram = $stokTelurOmegaGudang ? $stokTelurOmegaGudang->total_stok : 0;
        $stokTelurBiasaGram = $stokTelurBiasaGudang ? $stokTelurBiasaGudang->total_stok : 0;

        // ===============================
        // TELUR → KONVERSI KE KG (UNTUK TAMPILAN)
        // ===============================
        $stokTelurOmegaKg = $stokTelurOmegaGram / 1000;
        $stokTelurBiasaKg = $stokTelurBiasaGram / 1000;

        return view('gudang.dashboard', [
            'page' => 'Gudang',

            // KARTU (KG)
            'stok_telur_omega_gudang' => $stokTelurOmegaKg,
            'stok_telur_biasa_gudang' => $stokTelurBiasaKg,

            // MODAL (GRAM)
            'stok_telur_omega_gram' => $stokTelurOmegaGram,
            'stok_telur_biasa_gram' => $stokTelurBiasaGram,

            // Pakan
            'stok_pakan_grower' => $growerKg,
            'stok_pakan_layer'  => $layerKg,

            // Saldo
            'saldo_gudang' => $saldoGudang ? $saldoGudang->jumlah_saldo : 0,
        ]);
    }


    public function updateStokTelur(Request $request)
    {
        $request->validate([
            'jenis_telur' => 'required|in:omega,biasa',
            'jenis_stok' => 'required|in:gudang',
            'total_stok' => 'required|numeric|min:0',
        ]);

        // update khusus stok di GUDANG
        StokTelur::where('jenis_telur', $request->jenis_telur)
            ->where('jenis_stok', $request->jenis_stok)
            ->update([
                'total_stok' => $request->total_stok, // langsung set total stok sesuai input
            ]);

        return back()->with('success', 'Stok telur gudang berhasil diperbarui');
    }



    /*
    BARANG MASUK
    */
    public function barangMasuk()
    {
        // Ambil stok telur gudang
        $stokTelurGudang = StokTelur::where('jenis_stok', 'gudang')->first();
        $stokKg = $stokTelurGudang ? $stokTelurGudang->total_stok / 1000 : 0;

        // Filter tahun & bulan
        $tahun = request()->filled('tahun') ? request('tahun') : now()->year;
        $bulan = request()->filled('bulan') ? request('bulan') : now()->month; // default bulan ini

        $query = GudangBarangMasuk::query();

        // Filter tahun wajib
        $query->whereYear('tanggal_barang_masuk', $tahun);

        // Filter bulan hanya jika bukan 'all'
        if ($bulan !== 'all') {
            $query->whereMonth('tanggal_barang_masuk', $bulan);
        }

        $data_barang_masuk = $query->orderBy('tanggal_barang_masuk', 'desc')
            ->paginate(10)
            ->withQueryString();

        return view('gudang.barangMasuk.gudangBarangMasuk', [
            'page' => 'Gudang',
            'data_barang_masuk' => $data_barang_masuk,
            'stok_telur_gudang' => $stokKg,
            'bulan' => $bulan,
            'tahun' => $tahun,
        ]);
    }



    public function tambahBarangMasuk()
    {
        $kandang = kandang::get();
        return view('gudang.barangMasuk.gudangTambahBarangMasuk', [
            'page' => 'Gudang',
            'data_kandang' => $kandang
        ]);
    }

    public function storeBarangMasuk(Request $request)
    {
        //VALIDASI TAMBAH
        $request->validate([
            'tanggal_barang_masuk' => 'required',
            'kandang_id' => 'required',
            'jumlah_butir' => 'required|numeric',
            'jumlah_pecah' => 'required|numeric',
            'jumlah_gram' => 'required|numeric',
        ]);

        //tambah data ke tb_gudang_barang_masuk
        GudangBarangMasuk::create([
            'tanggal_barang_masuk' => $request->tanggal_barang_masuk,
            'nama_kandang' => $request->kandang_id,
            'jumlah_butir' => $request->jumlah_butir,
            'jumlah_pecah' => $request->jumlah_pecah,
            'jumlah_gram' => $request->jumlah_gram,
        ]);


        return redirect('/dashboard/gudang/barang-masuk')->with('messageTambahBarangMasuk', 'Berhasil Menambahkan Barang Masuk');
    }

    public function editBarangMasuk($id)
    {
        // //mengambil data spesifik dari tabel
        // $barang_masuk = GudangBarangMasuk::findOrFail($id);
        // $kandang = kandang::get();
        // return view('gudang.barangMasuk.gudangEditBarangMasuk', [
        //     'page' => 'Gudang',
        //     'data_kandang' => $kandang,
        //     'data_barang_masuk' => $barang_masuk
        // ]);
        $produksi = produksi::findOrFail($id);
        $kandang = kandang::get();
        return view('gudang.barangMasuk.gudangEditBarangMasuk', [
            'page' => 'Gudang',
            'data_kandang' => $kandang,
            'data_produksi' => $produksi
        ]);
    }

    public function updateBarangMasuk($id, Request $request)
    {
        // VALIDASI
        $request->validate([
            'tanggal_produksi' => 'required|date',
            'kandang_id' => 'required',
            'populasi_ayam' => 'required|numeric|min:1',
            'usia' => 'required|numeric|min:0',
            'jenis_telur' => 'required|string',
            'apkir' => 'required|numeric|min:0',
            'mati' => 'required|numeric|min:0',
            'jumlah_butir' => 'required|numeric|min:0',
            'jumlah_pecah' => 'required|numeric|min:0',
            'jumlah_gram' => 'required|numeric|min:0',
            'grower_per_ayam' => 'required|numeric|min:0',
            'layer_per_ayam' => 'required|numeric|min:0',
            'kegiatan' => 'required|string',
            'keterangan' => 'required|string',
        ]);

        try {
            DB::transaction(function () use ($request, $id) {

                /* ===============================
             * 1️⃣ DATA LAMA (WAJIB DI AWAL)
             * =============================== */
                $produksi = Produksi::lockForUpdate()->findOrFail($id);

                $jumlahGramLama = $produksi->jumlah_gram;
                $pakanLamaA     = $produksi->pakan_A;
                $pakanLamaB     = $produksi->pakan_B;

                $jenisTelurLama = $produksi->jenis_telur;
                $jenisTelurBaru = $request->jenis_telur;
                $jumlahGramBaru = $request->jumlah_gram;

                /* ===============================
             * 2️⃣ DATA KANDANG
             * =============================== */
                $kandang = Kandang::where('nama_kandang', $produksi->nama_kandang)
                    ->lockForUpdate()
                    ->firstOrFail();

                /* ===============================
             * 3️⃣ HITUNG ULANG PAKAN BARU
             * =============================== */
                $pakanBaruA = $request->populasi_ayam * $request->grower_per_ayam;
                $pakanBaruB = $request->populasi_ayam * $request->layer_per_ayam;

                /* ===============================
             * 4️⃣ UPDATE STOK PAKAN KANDANG
             * =============================== */
                $dataPakan = [
                    'Grower' => ['lama' => $pakanLamaA, 'baru' => $pakanBaruA],
                    'Layer'  => ['lama' => $pakanLamaB, 'baru' => $pakanBaruB],
                ];

                foreach ($dataPakan as $jenis => $nilai) {

                    $selisih = $nilai['baru'] - $nilai['lama'];
                    if ($selisih == 0) continue;

                    $stokPakan = StokPakan::where('jenis_pakan', $jenis)
                        ->lockForUpdate()
                        ->firstOrFail();

                    $kandangPakan = KandangPakan::where('kandang_id', $kandang->id)
                        ->where('stok_pakan_id', $stokPakan->id)
                        ->lockForUpdate()
                        ->firstOrFail();

                    if ($selisih > 0) {
                        if ($kandangPakan->stok < $selisih) {
                            throw new \Exception("Stok pakan $jenis tidak mencukupi");
                        }
                        $kandangPakan->decrement('stok', $selisih);
                    } else {
                        $kandangPakan->increment('stok', abs($selisih));
                    }
                }

                /* ===============================
             * 5️⃣ UPDATE PRODUKSI
             * =============================== */
                $produksi->update([
                    'tanggal_produksi' => $request->tanggal_produksi,
                    'nama_kandang' => $request->kandang_id,
                    'populasi_ayam' => $request->populasi_ayam,
                    'usia' => $request->usia,
                    'jenis_telur' => $jenisTelurBaru,
                    'apkir' => $request->apkir,
                    'mati' => $request->mati,
                    'jumlah_butir' => $request->jumlah_butir,
                    'jumlah_pecah' => $request->jumlah_pecah,
                    'jumlah_gram' => $jumlahGramBaru,
                    'grower_per_ayam' => $request->grower_per_ayam,
                    'layer_per_ayam' => $request->layer_per_ayam,
                    'pakan_A' => $pakanBaruA,
                    'pakan_B' => $pakanBaruB,
                    'persentase_produksi' => ($request->jumlah_butir / $request->populasi_ayam) * 100,
                    'kegiatan' => $request->kegiatan,
                    'keterangan' => $request->keterangan,
                ]);

                /* ===============================
             * 6️⃣ UPDATE STOK TELUR GUDANG (FIXED)
             * =============================== */

                // JIKA JENIS TELUR SAMA
                if ($jenisTelurLama === $jenisTelurBaru) {

                    $stokGudang = StokTelur::lockForUpdate()
                        ->where('jenis_stok', 'gudang')
                        ->where('jenis_telur', $jenisTelurBaru)
                        ->firstOrFail();

                    $selisihGram = $jumlahGramBaru - $jumlahGramLama;
                    $stokGudang->increment('total_stok', $selisihGram);
                } else {

                    // KURANGI STOK TELUR LAMA
                    $stokGudangLama = StokTelur::lockForUpdate()
                        ->where('jenis_stok', 'gudang')
                        ->where('jenis_telur', $jenisTelurLama)
                        ->firstOrFail();

                    $stokGudangLama->decrement('total_stok', $jumlahGramLama);

                    // TAMBAH STOK TELUR BARU
                    $stokGudangBaru = StokTelur::lockForUpdate()
                        ->where('jenis_stok', 'gudang')
                        ->where('jenis_telur', $jenisTelurBaru)
                        ->firstOrFail();

                    $stokGudangBaru->increment('total_stok', $jumlahGramBaru);
                }

                /* ===============================
             * 7️⃣ UPDATE GUDANG MASUK
             * =============================== */
                $produksi->gudangMasuk()->update([
                    'tanggal_barang_masuk' => $request->tanggal_produksi,
                    'nama_kandang' => $request->kandang_id,
                    'jenis_telur' => $jenisTelurBaru,
                    'jumlah_butir' => $request->jumlah_butir,
                    'jumlah_pecah' => $request->jumlah_pecah,
                    'jumlah_gram' => $jumlahGramBaru,
                ]);
            });

            return redirect('/dashboard/gudang/barang-masuk')
                ->with('messageUpdateBarangMasuk', 'Barang Masuk berhasil diupdate');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function destroyBarangMasuk($id)
    {

        try {
            DB::transaction(function () use ($id) {

                /* ===============================
             * 1️⃣ AMBIL DATA PRODUKSI LAMA
             * =============================== */
                $produksi = Produksi::lockForUpdate()->findOrFail($id);

                $jumlahGramLama = $produksi->jumlah_gram;
                $pakanLamaA     = $produksi->pakan_A;
                $pakanLamaB     = $produksi->pakan_B;

                /* ===============================
             * 2️⃣ DATA KANDANG
             * =============================== */
                $kandang = Kandang::where('nama_kandang', $produksi->nama_kandang)
                    ->lockForUpdate()
                    ->firstOrFail();

                /* ===============================
             * 3️⃣ KEMBALIKAN STOK PAKAN
             * =============================== */
                $dataPakan = [
                    'Grower' => $pakanLamaA,
                    'Layer'  => $pakanLamaB,
                ];

                foreach ($dataPakan as $jenis => $jumlah) {

                    if ($jumlah <= 0) continue;

                    $stokPakan = StokPakan::where('jenis_pakan', $jenis)
                        ->lockForUpdate()
                        ->firstOrFail();

                    $kandangPakan = KandangPakan::where('kandang_id', $kandang->id)
                        ->where('stok_pakan_id', $stokPakan->id)
                        ->lockForUpdate()
                        ->firstOrFail();

                    // ⬆️ Kembalikan pakan ke kandang
                    $kandangPakan->increment('stok', $jumlah);
                }

                /* ===============================
             * 4️⃣ KURANGI STOK TELUR GUDANG
             * =============================== */
                $stokGudang = StokTelur::lockForUpdate()
                    ->where('jenis_stok', 'gudang')
                    ->firstOrFail();

                // rollback telur
                $stokGudang->decrement('total_stok', $jumlahGramLama);

                /* ===============================
             * 5️⃣ DELETE GUDANG MASUK
             * =============================== */
                $produksi->gudangMasuk()->delete();

                /* ===============================
             * 6️⃣ DELETE PRODUKSI
             * =============================== */
                $produksi->delete();
            });

            return redirect('/dashboard/gudang/barang-masuk')->with('messageDeleteBarangMasuk', 'Berhasil Hapus Barang Masuk');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function cetakBarangMasuk()
    {
        $bulan = request()->filled('bulan') ? request('bulan') : null;
        $tahun = request()->filled('tahun') ? request('tahun') : now()->year;

        $query = GudangBarangMasuk::query();
        $query->whereYear('tanggal_barang_masuk', $tahun);

        if ($bulan) {
            $query->whereMonth('tanggal_barang_masuk', $bulan);
        }

        $data_barang_masuk = $query
            ->orderBy('tanggal_barang_masuk', 'asc')
            ->get(); // ambil semua data, bukan paginate

        $periode = $bulan
            ? \Carbon\Carbon::createFromDate($tahun, $bulan, 1)->translatedFormat('F Y')
            : "Tahun $tahun";

        // Load PDF
        $pdf = Pdf::loadView('gudang.barangMasuk.barangMasukLaporan', [
            'data_barang_masuk' => $data_barang_masuk,
            'periode' => $periode
        ])->setPaper('A4', 'portrait');

        // Nama file unik agar HP tidak cache PDF lama
        $filename = "laporan-barang-masuk-$periode-" . time() . ".pdf";

        return response($pdf->stream($filename))
            ->header('Cache-Control', 'no-cache, no-store, must-revalidate')
            ->header('Pragma', 'no-cache')
            ->header('Expires', '0')
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', "inline; filename=\"$filename\"");
    }



    /*
    BARANG Keluar
    */
    public function barangKeluar(Request $request)
    {
        $query = GudangBarangKeluar::query();

        // Tahun: default sekarang jika tidak diisi
        $tahun = $request->filled('tahun') ? $request->tahun : now()->year;

        // Bulan:
        // - Jika request 'bulan' = 'all' → null (tidak filter)
        // - Jika tidak diisi → default bulan sekarang
        if ($request->filled('bulan')) {
            $bulan = $request->bulan === 'all' ? null : $request->bulan;
        } else {
            $bulan = now()->month;
        }

        // Filter tahun wajib
        $query->whereYear('tanggal_barang_keluar', $tahun);

        // Filter bulan jika ada
        if ($bulan) {
            $query->whereMonth('tanggal_barang_keluar', $bulan);
        }

        // Ambil data dengan paginate
        $data_barang_keluar = $query->orderBy('tanggal_barang_keluar', 'desc')
            ->paginate(10)
            ->withQueryString();

        // Periode untuk ditampilkan
        $periode = $bulan
            ? \Carbon\Carbon::createFromDate($tahun, $bulan, 1)->translatedFormat('F Y')
            : "Tahun $tahun";

        return view('gudang.barangKeluar.gudangBarangKeluar', [
            'page' => 'Gudang',
            'data_barang_keluar' => $data_barang_keluar,
            'periode' => $periode,
            'bulan' => $request->filled('bulan') ? $request->bulan : $bulan, // untuk select bulan di blade
            'tahun' => $tahun,
        ]);
    }


    public function cetakBarangKeluar(Request $request)
    {
        $bulan = $request->filled('bulan') ? $request->bulan : null;
        $tahun = $request->filled('tahun') ? $request->tahun : now()->year;

        $query = GudangBarangKeluar::query();
        $query->whereYear('tanggal_barang_keluar', $tahun);

        if ($bulan) {
            $query->whereMonth('tanggal_barang_keluar', $bulan);
        }

        $data_barang_keluar = $query->orderBy('tanggal_barang_keluar', 'asc')->get();

        $periode = $bulan
            ? \Carbon\Carbon::createFromDate($tahun, $bulan, 1)->translatedFormat('F Y')
            : "Tahun $tahun";

        // Load PDF
        $pdf = Pdf::loadView('gudang.barangKeluar.barangKeluarLaporan', [
            'data_barang_keluar' => $data_barang_keluar,
            'periode' => $periode
        ])->setPaper('A4', 'portrait');

        // Nama file unik agar browser HP tidak cache PDF lama
        $filename = "laporan-barang-keluar-$periode-" . time() . ".pdf";

        return response($pdf->stream($filename))
            ->header('Cache-Control', 'no-cache, no-store, must-revalidate')
            ->header('Pragma', 'no-cache')
            ->header('Expires', '0')
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', "inline; filename=\"$filename\"");
    }




    public function tambahBarangKeluar()
    {
        return view('gudang.barangKeluar.gudangTambahBarangKeluar', [
            'page' => 'Gudang',
        ]);
    }

    public function storeBarangKeluar(Request $request)
    {
        // =========================
        // 1. VALIDASI INPUT DINAMIS
        // =========================
        $baseRules = [
            'tanggal_barang_keluar' => 'required|date',
            'nama_konsumen' => 'required|string|max:255',
            'jenis_barang' => 'required|in:telur,ayam_apkir',
        ];

        if ($request->jenis_barang === 'telur') {
            $rules = array_merge($baseRules, [
                'jenis_telur' => 'required|string',
                'jumlah_barang_keluar' => 'required|numeric|min:1',
                'harga_kilo' => 'required|numeric|min:0',
                'total_harga' => 'required|numeric|min:0',
            ]);
        } else { // ayam_apkir
            $rules = array_merge($baseRules, [
                'jumlah_ayam' => 'required|numeric|min:1',
                'total_harga_ayam' => 'required|numeric|min:0',
            ]);
        }

        $request->validate($rules);

        try {
            DB::transaction(function () use ($request) {

                if ($request->jenis_barang === 'telur') {
                    $this->storeBarangKeluarTelur($request);
                } else {
                    $this->storeBarangKeluarAyamApkir($request);
                }
            });

            // =========================
            // JIKA BERHASIL
            // =========================
            return redirect('/dashboard/gudang/barang-keluar')
                ->with('messageTambahBarangKeluar', 'Berhasil Menambahkan Barang Keluar');
        } catch (\Exception $e) {
            // =========================
            // JIKA GAGAL
            // =========================
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }
    }

    /**
     * Helper: Store Barang Keluar TELUR
     */
    private function storeBarangKeluarTelur(Request $request)
    {
        // =========================
        // 2. SIMPAN BARANG KELUAR (TELUR)
        // =========================
        $barangKeluar = GudangBarangKeluar::create([
            'tanggal_barang_keluar' => $request->tanggal_barang_keluar,
            'nama_konsumen' => $request->nama_konsumen,
            'jenis_barang' => 'telur',
            'jenis_telur' => $request->jenis_telur,
            'jumlah_barang_keluar' => $request->jumlah_barang_keluar,
            'harga_kilo' => $request->harga_kilo,
            'total_harga' => $request->total_harga,
            'jumlah_ayam' => null,
            'harga_ayam' => null,
        ]);

        // =========================
        // 3. SIMPAN BARANG MASUK DI EGG GROW (DENGAN RELASI)
        // =========================
        EggGrowBarangMasuk::create([
            'gudang_barang_keluar_id' => $barangKeluar->id, // RELASI PENTING
            'tanggal_barang_masuk'    => $request->tanggal_barang_keluar,
            'jenis_telur'             => $request->jenis_telur,
            'jumlah_barang_masuk'     => $request->jumlah_barang_keluar,
            'harga_kilo'              => $request->harga_kilo,
            'total_harga'             => $request->total_harga,
        ]);

        // =========================
        // 4. STOK TELUR - GUDANG
        // =========================
        $stokGudang = StokTelur::lockForUpdate()
            ->where('jenis_stok', 'gudang')
            ->where('jenis_telur', $request->jenis_telur)
            ->first();

        if ($stokGudang->total_stok < $request->jumlah_barang_keluar) {
            throw new \Exception(
                "Stok Telur {$request->jenis_telur} tidak mencukupi. Stok tersedia: {$stokGudang->total_stok} gr"
            );
        }

        $stokGudang->decrement('total_stok', $request->jumlah_barang_keluar);

        // =========================
        // 5. STOK TELUR - TOKO
        // =========================
        $stokToko = StokTelur::lockForUpdate()
            ->where('jenis_stok', 'toko')
            ->where('jenis_telur', $request->jenis_telur)
            ->first();

        $stokToko->increment('total_stok', $request->jumlah_barang_keluar);

        // =========================
        // 6. SALDO - GUDANG
        // =========================
        $saldoGudang = Saldo::where('jenis_saldo', 'gudang')
            ->lockForUpdate()
            ->firstOrFail();

        $saldoGudang->increment('jumlah_saldo', $request->total_harga);

        // =========================
        // 7. SALDO - TOKO
        // =========================
        $saldoToko = Saldo::where('jenis_saldo', 'toko')
            ->lockForUpdate()
            ->firstOrFail();

        if ($saldoToko->jumlah_saldo < $request->total_harga) {
            throw new \Exception(
                "Saldo toko tidak mencukupi. Saldo tersedia: {$saldoToko->jumlah_saldo}"
            );
        }
        $jumlahKilo = $request->jumlah_barang_keluar / 1000;
        $saldoToko->decrement('jumlah_saldo', $request->total_harga);

        // =========================
        // 8. BUAT PENGELUARAN UNTUK EGG GROW
        // =========================
        PengeluaranToko::create([
            'jenis_pengeluaran' => 'beli telur',
            'tanggal' => $request->tanggal_barang_keluar,
            'nama_pengeluaran' => "Telur {$request->jenis_telur}",
            'berat_total' => $request->jumlah_barang_keluar,
            'jenis_telur' => $request->jenis_telur,
            'nominal' => $request->total_harga,
            'keterangan' => "Membeli Telur {$jumlahKilo} kg dari Gudang",
        ]);
    }

    /**
     * Helper: Store Barang Keluar AYAM APKIR
     */
    private function storeBarangKeluarAyamApkir(Request $request)
    {
        // =========================
        // SIMPAN BARANG KELUAR (AYAM APKIR)
        // =========================
        GudangBarangKeluar::create([
            'tanggal_barang_keluar' => $request->tanggal_barang_keluar,
            'nama_konsumen' => $request->nama_konsumen,
            'jenis_barang' => 'ayam_apkir',
            'jenis_telur' => null, // tidak ada untuk ayam
            'jumlah_barang_keluar' => null, // tidak ada untuk ayam
            'harga_kilo' => null, // tidak ada untuk ayam
            'total_harga' => $request->total_harga_ayam,
            'jumlah_ayam' => $request->jumlah_ayam,
            'harga_ayam' => null, // tidak ada, total diinput manual
        ]);

        // =========================
        // UPDATE SALDO - GUDANG SAJA
        // =========================
        $saldoGudang = Saldo::where('jenis_saldo', 'gudang')
            ->lockForUpdate()
            ->firstOrFail();

        $saldoGudang->increment('jumlah_saldo', $request->total_harga_ayam);
    }


    public function editBarangKeluar($id)
    {
        //mengambil data spesifik dari tabel
        $barang_keluar = GudangBarangKeluar::findOrFail($id);

        return view('gudang.barangKeluar.gudangEditBarangKeluar', [
            'page' => 'Gudang',
            'data_barang_keluar' => $barang_keluar
        ]);
    }

    public function updateBarangKeluar(Request $request, $id)
    {
        // =========================
        // VALIDASI DINAMIS
        // =========================
        $baseRules = [
            'tanggal_barang_keluar' => 'required|date',
            'nama_konsumen' => 'required|string|max:255',
        ];

        $barangKeluar = GudangBarangKeluar::findOrFail($id);

        if ($barangKeluar->jenis_barang === 'telur') {
            $rules = array_merge($baseRules, [
                'jenis_telur' => 'required|string',
                'jumlah_barang_keluar' => 'required|numeric|min:1',
                'harga_kilo' => 'required|numeric|min:0',
            ]);
        } else { // ayam_apkir
            $rules = array_merge($baseRules, [
                'jumlah_ayam' => 'required|numeric|min:1',
                'total_harga_ayam' => 'required|numeric|min:0',
            ]);
        }

        $request->validate($rules);

        try {
            DB::transaction(function () use ($request, $id, $barangKeluar) {

                $barangKeluar = GudangBarangKeluar::lockForUpdate()->findOrFail($id);

                // Simpan nilai lama untuk referensi update relasi
                $totalHargaLama = $barangKeluar->total_harga;
                $tanggalLama = $barangKeluar->tanggal_barang_keluar;
                $jenisTelurLama = $barangKeluar->jenis_telur;

                // =========================
                // JIKA TELUR
                // =========================
                if ($barangKeluar->jenis_barang === 'telur') {

                    // Hitung total baru
                    $totalBaru = ($request->jumlah_barang_keluar / 1000) * $request->harga_kilo;

                    /* Rollback data lama */
                    StokTelur::where('jenis_stok', 'gudang')
                        ->where('jenis_telur', $barangKeluar->jenis_telur)
                        ->lockForUpdate()
                        ->increment('total_stok', $barangKeluar->jumlah_barang_keluar);

                    StokTelur::where('jenis_stok', 'toko')
                        ->where('jenis_telur', $barangKeluar->jenis_telur)
                        ->lockForUpdate()
                        ->decrement('total_stok', $barangKeluar->jumlah_barang_keluar);

                    Saldo::where('jenis_saldo', 'gudang')
                        ->lockForUpdate()
                        ->decrement('jumlah_saldo', $barangKeluar->total_harga);

                    Saldo::where('jenis_saldo', 'toko')
                        ->lockForUpdate()
                        ->increment('jumlah_saldo', $barangKeluar->total_harga);

                    /* Validasi data baru */
                    $stokGudang = StokTelur::where('jenis_stok', 'gudang')
                        ->where('jenis_telur', $request->jenis_telur)
                        ->lockForUpdate()
                        ->firstOrFail();

                    if ($stokGudang->total_stok < $request->jumlah_barang_keluar) {
                        throw new \Exception("Stok gudang tidak mencukupi");
                    }

                    $saldoToko = Saldo::where('jenis_saldo', 'toko')
                        ->lockForUpdate()
                        ->firstOrFail();

                    if ($saldoToko->jumlah_saldo < $totalBaru) {
                        throw new \Exception("Saldo toko tidak mencukupi");
                    }

                    /* Terapkan data baru */
                    $stokGudang->decrement('total_stok', $request->jumlah_barang_keluar);

                    StokTelur::where('jenis_stok', 'toko')
                        ->where('jenis_telur', $request->jenis_telur)
                        ->lockForUpdate()
                        ->increment('total_stok', $request->jumlah_barang_keluar);

                    Saldo::where('jenis_saldo', 'gudang')
                        ->lockForUpdate()
                        ->increment('jumlah_saldo', $totalBaru);

                    $saldoToko->decrement('jumlah_saldo', $totalBaru);

                    /* Update data */
                    $barangKeluar->update([
                        'tanggal_barang_keluar' => $request->tanggal_barang_keluar,
                        'nama_konsumen' => $request->nama_konsumen,
                        'jenis_telur' => $request->jenis_telur,
                        'jumlah_barang_keluar' => $request->jumlah_barang_keluar,
                        'harga_kilo' => $request->harga_kilo,
                        'total_harga' => $totalBaru,
                    ]);

                    /* Update relasi */
                    EggGrowBarangMasuk::where('gudang_barang_keluar_id', $barangKeluar->id)
                        ->update([
                            'tanggal_barang_masuk' => $request->tanggal_barang_keluar,
                            'jenis_telur' => $request->jenis_telur,
                            'jumlah_barang_masuk' => $request->jumlah_barang_keluar,
                            'harga_kilo' => $request->harga_kilo,
                            'total_harga' => $totalBaru,
                        ]);

                    $jumlahKilo = $request->jumlah_barang_keluar / 1000;
                    /* Update pengeluaran egg grow - gunakan nilai LAMA untuk WHERE */
                    PengeluaranToko::where('tanggal', $tanggalLama)
                        ->where('jenis_pengeluaran', 'beli telur')
                        ->where('nama_pengeluaran', 'like', 'Telur%')
                        ->where('nominal', $totalHargaLama)
                        ->lockForUpdate()
                        ->update([
                            'tanggal' => $request->tanggal_barang_keluar,
                            'nama_pengeluaran' => "Telur {$request->jenis_telur}",
                            'berat_total' => $request->jumlah_barang_keluar,
                            'nominal' => $totalBaru,
                            'jenis_telur' => $request->jenis_telur,
                            'keterangan' => "Membeli Telur {$jumlahKilo} kg dari Gudang",
                        ]);
                }

                // =========================
                // JIKA AYAM APKIR
                // =========================
                else {

                    $totalBaru = $request->total_harga_ayam;

                    /* Rollback data lama */
                    Saldo::where('jenis_saldo', 'gudang')
                        ->lockForUpdate()
                        ->decrement('jumlah_saldo', $barangKeluar->total_harga);

                    /* Update data */
                    $barangKeluar->update([
                        'tanggal_barang_keluar' => $request->tanggal_barang_keluar,
                        'nama_konsumen' => $request->nama_konsumen,
                        'jumlah_ayam' => $request->jumlah_ayam,
                        'total_harga' => $totalBaru,
                    ]);

                    /* Terapkan data baru */
                    Saldo::where('jenis_saldo', 'gudang')
                        ->lockForUpdate()
                        ->increment('jumlah_saldo', $totalBaru);
                }
            });

            return redirect('/dashboard/gudang/barang-keluar')
                ->with('messageUpdateBarangKeluar', 'Barang keluar berhasil diperbarui');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }
    }



    public function destroyBarangKeluar($id)
    {
        try {
            DB::transaction(function () use ($id) {

                $barangKeluar = GudangBarangKeluar::lockForUpdate()->findOrFail($id);

                // =========================
                // JIKA JENIS TELUR
                // =========================
                if ($barangKeluar->jenis_barang === 'telur') {

                    /* =========================
                   1. VALIDASI ROLLBACK
                ========================= */

                    $stokToko = StokTelur::where('jenis_stok', 'toko')
                        ->where('jenis_telur', $barangKeluar->jenis_telur)
                        ->lockForUpdate()
                        ->firstOrFail();

                    if ($stokToko->total_stok < $barangKeluar->jumlah_barang_keluar) {
                        throw new \Exception(
                            "Stok toko tidak mencukupi untuk rollback penghapusan"
                        );
                    }

                    $saldoGudang = Saldo::where('jenis_saldo', 'gudang')
                        ->lockForUpdate()
                        ->firstOrFail();

                    if ($saldoGudang->jumlah_saldo < $barangKeluar->total_harga) {
                        throw new \Exception(
                            "Saldo gudang tidak mencukupi untuk rollback penghapusan"
                        );
                    }

                    /* =========================
                   2. ROLLBACK DATA
                ========================= */

                    // stok gudang +
                    StokTelur::where('jenis_stok', 'gudang')
                        ->where('jenis_telur', $barangKeluar->jenis_telur)
                        ->lockForUpdate()
                        ->increment('total_stok', $barangKeluar->jumlah_barang_keluar);

                    // stok toko -
                    $stokToko->decrement('total_stok', $barangKeluar->jumlah_barang_keluar);

                    // saldo gudang -
                    $saldoGudang->decrement('jumlah_saldo', $barangKeluar->total_harga);

                    // saldo toko +
                    Saldo::where('jenis_saldo', 'toko')
                        ->lockForUpdate()
                        ->increment('jumlah_saldo', $barangKeluar->total_harga);

                    /* =========================
                   3. HAPUS DATA RELASI
                ========================= */

                    EggGrowBarangMasuk::where('gudang_barang_keluar_id', $barangKeluar->id)->delete();

                    // HAPUS PENGELUARAN EGG GROW TERKAIT
                    PengeluaranToko::where('tanggal', $barangKeluar->tanggal_barang_keluar)
                        ->where('jenis_pengeluaran', 'beli telur')
                        ->where('nama_pengeluaran', 'like', 'Telur%')
                        ->where('nominal', $barangKeluar->total_harga)
                        ->delete();
                }
                // =========================
                // JIKA JENIS AYAM APKIR
                // =========================
                elseif ($barangKeluar->jenis_barang === 'ayam_apkir') {

                    // Hanya kurangi saldo gudang
                    $saldoGudang = Saldo::where('jenis_saldo', 'gudang')
                        ->lockForUpdate()
                        ->firstOrFail();

                    if ($saldoGudang->jumlah_saldo < $barangKeluar->total_harga) {
                        throw new \Exception(
                            "Saldo gudang tidak mencukupi untuk rollback penghapusan"
                        );
                    }

                    $saldoGudang->decrement('jumlah_saldo', $barangKeluar->total_harga);
                }

                /* =========================
               4. HAPUS DATA UTAMA
            ========================= */

                $barangKeluar->delete();
            });

            return redirect('/dashboard/gudang/barang-keluar')
                ->with('messageDeleteBarangKeluar', 'Berhasil menghapus barang keluar');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function pengeluaran()
    {
        // Jika request ADA → pakai request
        // Jika TIDAK ADA → default bulan sekarang
        $bulan = request()->has('bulan')
            ? request('bulan')
            : now()->month;

        $tahun = request()->filled('tahun')
            ? request('tahun')
            : now()->year;

        $query = Pengeluaran::query();
        $query->whereYear('tanggal', $tahun);

        // 🔑 HANYA filter bulan jika:
        // - bulan bukan null
        // - dan bukan 'all'
        if ($bulan && $bulan !== 'all') {
            $query->whereMonth('tanggal', $bulan);
        }

        $data_pengeluaran = $query
            ->orderBy('tanggal', 'desc')
            ->paginate(10);

        return view('gudang.pengeluaran.pengeluaran', [
            'page' => 'Gudang',
            'data_pengeluaran' => $data_pengeluaran,
            'bulan' => $bulan,
            'tahun' => $tahun,
        ]);
    }



    public function cetakPengeluaran()
    {
        $bulan = request()->filled('bulan') ? request('bulan') : null;
        $tahun = request()->filled('tahun') ? request('tahun') : now()->year;

        $query = Pengeluaran::query();
        $query->whereYear('tanggal', $tahun);

        if ($bulan) {
            $query->whereMonth('tanggal', $bulan);
        }

        $data_pengeluaran = $query->orderBy('tanggal', 'asc')->get(); // ambil semua data

        $periode = $bulan
            ? \Carbon\Carbon::createFromDate($tahun, $bulan, 1)->translatedFormat('F Y')
            : "Tahun $tahun";

        // Load PDF
        $pdf = Pdf::loadView('gudang.pengeluaran.pengeluaranLaporan', [
            'data_pengeluaran' => $data_pengeluaran,
            'periode' => $periode
        ])->setPaper('A4', 'landscape');

        // Nama file unik agar browser HP tidak cache PDF lama
        $filename = "laporan-pengeluaran-$periode-" . time() . ".pdf";

        return response($pdf->stream($filename))
            ->header('Cache-Control', 'no-cache, no-store, must-revalidate')
            ->header('Pragma', 'no-cache')
            ->header('Expires', '0')
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', "inline; filename=\"$filename\"");
    }


    public function tambahPengeluaran()
    {
        return view('gudang.pengeluaran.tambahPengeluaran', [
            'page' => 'Gudang',
        ]);
    }

    public function storePengeluaran(Request $request)
    {
        // 1️⃣ VALIDASI DINAMIS
        $baseRules = [
            'tanggal' => 'required|date',
            'jenis_pengeluaran' => 'required|in:pakan,lainnya',
            'keterangan' => 'nullable|string',
        ];

        if ($request->jenis_pengeluaran === 'pakan') {
            $rules = array_merge($baseRules, [
                'jenis_pakan' => 'required|string',
                'berat_total' => 'required|numeric|min:1',
                'harga_kilo'  => 'required|numeric|min:0',
            ]);
        } else { // lainnya
            $rules = array_merge($baseRules, [
                'nama_pengeluaran' => 'required|string',
                'nominal_lainnya'  => 'required|numeric|min:1',
            ]);
        }

        $request->validate($rules);

        try {
            DB::transaction(function () use ($request) {

                // Ambil saldo gudang
                $saldo = Saldo::lockForUpdate()
                    ->where('jenis_saldo', 'gudang')
                    ->firstOrFail();

                // Hitung total harga
                $totalHarga = $request->jenis_pengeluaran === 'pakan'
                    ? round($request->berat_total  * $request->harga_kilo)
                    : (int) $request->nominal_lainnya;

                if ($totalHarga <= 0) throw new \Exception('Total pengeluaran tidak valid');
                if ($saldo->jumlah_saldo < $totalHarga) throw new \Exception('Saldo gudang tidak mencukupi');

                // Simpan pengeluaran
                Pengeluaran::create([
                    'tanggal' => $request->tanggal,
                    'jenis_pengeluaran' => $request->jenis_pengeluaran,
                    'jenis_pakan' => $request->jenis_pengeluaran === 'pakan' ? $request->jenis_pakan : null,
                    'berat_total' => $request->jenis_pengeluaran === 'pakan' ? $request->berat_total : null,
                    'harga_kilo' => $request->jenis_pengeluaran === 'pakan' ? $request->harga_kilo : null,
                    'nama_pengeluaran' => $request->jenis_pengeluaran === 'lainnya' ? $request->nama_pengeluaran : null,
                    'total_harga' => $totalHarga,
                    'keterangan' => $request->keterangan,
                ]);

                if ($request->jenis_pengeluaran === 'pakan') {
                    // Simpan ke tb_pakan_masuk (selalu create baru)
                    PakanMasuk::create([
                        'tanggal_pakan_masuk' => $request->tanggal,
                        'jenis_pakan' => $request->jenis_pakan,
                        'berat_total' => ($request->berat_total * 1000),
                        'harga_kilo' => $request->harga_kilo,
                        'total_harga' => $totalHarga,
                    ]);

                    // Update / tambah tb_stok_pakan
                    $stok = StokPakan::lockForUpdate()
                        ->where('jenis_pakan', $request->jenis_pakan)
                        ->first();

                    if ($stok) {
                        $stok->increment('berat_total', ($request->berat_total * 1000));
                    } else {
                        StokPakan::create([
                            'jenis_pakan' => $request->jenis_pakan,
                            'berat_total' => ($request->berat_total * 1000),
                        ]);
                    }
                }

                // Kurangi saldo
                $saldo->decrement('jumlah_saldo', $totalHarga);
            });

            return redirect()->route('gudang.pengeluaran')
                ->with('messageTambahPengeluaran', 'Pengeluaran berhasil disimpan');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function editPengeluaran($id)
    {
        $pengeluaran = Pengeluaran::findOrFail($id);

        return view('gudang.pengeluaran.editPengeluaran', [
            'page' => 'Gudang',
            'pengeluaran' => $pengeluaran,
        ]);
    }

    public function updatePengeluaran(Request $request, $id)
    {
        $pengeluaran = Pengeluaran::findOrFail($id);

        // 1️⃣ VALIDASI DINAMIS
        $baseRules = [
            'tanggal' => 'required|date',
            'jenis_pengeluaran' => 'required|in:pakan,lainnya',
            'keterangan' => 'nullable|string',
        ];

        if ($request->jenis_pengeluaran === 'pakan') {
            $rules = array_merge($baseRules, [
                'jenis_pakan' => 'required|string',
                'berat_total' => 'required|numeric|min:1',
                'harga_kilo'  => 'required|numeric|min:0',
            ]);
        } else { // lainnya
            $rules = array_merge($baseRules, [
                'nama_pengeluaran' => 'required|string',
                'nominal_lainnya'  => 'required|numeric|min:1',
            ]);
        }

        $request->validate($rules);

        try {
            DB::transaction(function () use ($request, $pengeluaran) {

                // 2️⃣ AMBIL SALDO
                $saldo = Saldo::lockForUpdate()
                    ->where('jenis_saldo', 'gudang')
                    ->firstOrFail();

                // 3️⃣ KEMBALIKAN STOK LAMA JIKA PAKAN
                if ($pengeluaran->jenis_pengeluaran === 'pakan') {
                    $stokLama = StokPakan::lockForUpdate()
                        ->where('jenis_pakan', $pengeluaran->jenis_pakan)
                        ->first();

                    if ($stokLama) {
                        $stokLama->decrement('berat_total', $pengeluaran->berat_total * 1000);
                    }

                    $pakanMasukLama = PakanMasuk::where('tanggal_pakan_masuk', $pengeluaran->tanggal)
                        ->where('jenis_pakan', $pengeluaran->jenis_pakan)
                        ->first();

                    if ($pakanMasukLama) {
                        $pakanMasukLama->delete();
                    }

                    // kembalikan saldo sebelumnya
                    $saldo->increment('jumlah_saldo', $pengeluaran->total_harga);
                }

                // 4️⃣ HITUNG TOTAL HARGA BARU
                $totalHarga = $request->jenis_pengeluaran === 'pakan'
                    ? round($request->berat_total * $request->harga_kilo)
                    : (int) $request->nominal_lainnya;

                if ($totalHarga <= 0) throw new \Exception('Total pengeluaran tidak valid');
                if ($saldo->jumlah_saldo < $totalHarga) throw new \Exception('Saldo gudang tidak mencukupi');

                // 5️⃣ UPDATE PENGELUARAN
                $pengeluaran->update([
                    'tanggal' => $request->tanggal,
                    'jenis_pengeluaran' => $request->jenis_pengeluaran,
                    'jenis_pakan' => $request->jenis_pengeluaran === 'pakan' ? $request->jenis_pakan : null,
                    'berat_total' => $request->jenis_pengeluaran === 'pakan' ? $request->berat_total : null,
                    'harga_kilo' => $request->jenis_pengeluaran === 'pakan' ? $request->harga_kilo : null,
                    'nama_pengeluaran' => $request->jenis_pengeluaran === 'lainnya' ? $request->nama_pengeluaran : null,
                    'total_harga' => $totalHarga,
                    'keterangan' => $request->keterangan,
                ]);

                // 6️⃣ JIKA PAKAN, UPDATE TB_PAKAN_MASUK & TB_STOK_PAKAN
                if ($request->jenis_pengeluaran === 'pakan') {

                    // simpan pakan masuk baru
                    PakanMasuk::create([
                        'tanggal_pakan_masuk' => $request->tanggal,
                        'jenis_pakan'   => $request->jenis_pakan,
                        'berat_total'   => $request->berat_total * 1000,
                        'harga_kilo'    => $request->harga_kilo,
                        'total_harga'   => $totalHarga,
                    ]);

                    // update stok pakan
                    $stok = StokPakan::lockForUpdate()
                        ->where('jenis_pakan', $request->jenis_pakan)
                        ->first();

                    if ($stok) {
                        $stok->increment('berat_total', $request->berat_total * 1000);
                    } else {
                        StokPakan::create([
                            'jenis_pakan' => $request->jenis_pakan,
                            'berat_total' => $request->berat_total * 1000,
                        ]);
                    }

                    // kurangi saldo
                    $saldo->decrement('jumlah_saldo', $totalHarga);
                }
            });

            return redirect()->route('gudang.pengeluaran')->with('messageUpdatePengeluaran', 'Pengeluaran berhasil diperbarui');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function destroyPengeluaran($id)
    {
        $pengeluaran = Pengeluaran::findOrFail($id);

        try {
            DB::transaction(function () use ($pengeluaran) {

                // 1️⃣ Ambil saldo gudang
                $saldo = Saldo::lockForUpdate()
                    ->where('jenis_saldo', 'gudang')
                    ->firstOrFail();

                // 2️⃣ Jika pengeluaran adalah pakan, kembalikan stok dan hapus PakanMasuk
                if ($pengeluaran->jenis_pengeluaran === 'pakan') {

                    // a. Kembalikan stok lama
                    $stok = StokPakan::lockForUpdate()
                        ->where('jenis_pakan', $pengeluaran->jenis_pakan)
                        ->first();

                    if ($stok) {
                        $stok->decrement('berat_total', $pengeluaran->berat_total * 1000);
                    }

                    // b. Hapus entri pakan_masuk terkait
                    $pakanMasuk = PakanMasuk::where('tanggal_pakan_masuk', $pengeluaran->tanggal)
                        ->where('jenis_pakan', $pengeluaran->jenis_pakan)
                        ->first();

                    if ($pakanMasuk) {
                        $pakanMasuk->delete();
                    }

                    // c. Kembalikan saldo
                    $saldo->increment('jumlah_saldo', $pengeluaran->total_harga);
                } else {
                    // 3️⃣ Jika pengeluaran bukan pakan, kembalikan saldo saja
                    $saldo->increment('jumlah_saldo', $pengeluaran->total_harga);
                }

                // 4️⃣ Hapus pengeluaran
                $pengeluaran->delete();
            });

            return redirect()->route('gudang.pengeluaran')->with('messageDeletePengeluaran', 'Pengeluaran berhasil dihapus');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
