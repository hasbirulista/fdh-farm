<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kandang;
use App\Models\KandangPakan;
use App\Models\StokPakan;
use App\Models\StokTelur;
use App\Models\Produksi;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class KandangController extends Controller
{
    public function kandang()
    {
        $kandangs = Kandang::with([
            'kandangPakan.stokPakan'
        ])
            ->orderBy('nama_kandang')
            ->get();

        // Ambil data produksi terakhir untuk setiap kandang
        $kandangs = $kandangs->map(function ($kandang) {
            $lastProduksi = Produksi::where('nama_kandang', $kandang->nama_kandang)
                ->orderBy('tanggal_produksi', 'desc')
                ->first();

            $kandang->lastProduksi = $lastProduksi;
            return $kandang;
        });

        return view('kandang.kandang', [
            'page' => 'Kandang',
            'kandangs' => $kandangs
        ]);
    }

    public function updatePakan(Request $request)
    {
        $request->validate([
            'kandang_id' => 'required|exists:tb_kandang,id',
            'jenis_pakan' => 'required|in:Grower,Layer',
            'stok' => 'required|numeric|min:0',
        ]);

        $kandangPakan = KandangPakan::where('kandang_id', $request->kandang_id)
            ->whereHas('stokPakan', function ($q) use ($request) {
                $q->where('jenis_pakan', $request->jenis_pakan);
            })
            ->first();

        if (!$kandangPakan) {
            return back()->with('error', 'Data pakan tidak ditemukan.');
        }

        $kandangPakan->update([
            'stok' => $request->stok, 
        ]);

        return back()->with('success', 'Stok pakan berhasil diperbarui.');
    }


    /*
    PRODUKSI
    */
    public function produksi()
    {
        $bulan = request()->filled('bulan') ? request('bulan') : now()->month;
        $tahun = request()->filled('tahun') ? request('tahun') : now()->year;

        $query = Produksi::query();

        // WAJIB: default bulan & tahun sekarang
        $query->whereMonth('tanggal_produksi', $bulan)
            ->whereYear('tanggal_produksi', $tahun);

        $data_produksi = $query
            ->orderBy('tanggal_produksi', 'desc')
            ->paginate(10)
            ->withQueryString();

        $daftarKandang = Kandang::orderBy('nama_kandang')->get();

        return view('kandang.produksiKandang', [
            'page' => 'Kandang',
            'data_produksi' => $data_produksi,
            'daftarKandang' => $daftarKandang,
            'bulanSekarang' => now()->translatedFormat('F Y'),
        ]);
    }



    public function produksiPerKandang($namaKandang = null)
    {
        $user = Auth::user();

        // ================= FILTER =================
        $tahun = request()->filled('tahun') ? request('tahun') : now()->year;

        // bulan NULL = semua bulan
        $bulan = request()->filled('bulan') ? request('bulan') : null;

        // default pertama kali masuk → bulan sekarang
        if (!request()->has('bulan') && !request()->has('tahun')) {
            $bulan = now()->month;
        }

        $bulanSekarang = $bulan
            ? \Carbon\Carbon::createFromDate($tahun, $bulan, 1)->translatedFormat('F Y')
            : "Tahun $tahun";

        /*
    |--------------------------------------------------------------------------
    | ROLE: ANAK KANDANG
    |--------------------------------------------------------------------------
    */
        if ($user->role === 'anak_kandang') {

            abort_if(!$user->kandang_id, 403);

            $kandang = Kandang::findOrFail($user->kandang_id);
            $namaKandang = $kandang->nama_kandang;
            $daftarKandang = collect([$kandang]);

            $query = Produksi::where('nama_kandang', $namaKandang)
                ->whereYear('tanggal_produksi', $tahun);

            if ($bulan) {
                $query->whereMonth('tanggal_produksi', $bulan);
            }

            $data_produksi = $query
                ->orderBy('tanggal_produksi', 'desc')
                ->paginate(10)
                ->withQueryString();

            $rataRataProduksi = Produksi::where('nama_kandang', $namaKandang)
                ->whereYear('tanggal_produksi', $tahun)
                ->when($bulan, fn($q) => $q->whereMonth('tanggal_produksi', $bulan))
                ->avg('persentase_produksi');

            $totalBeratGram = Produksi::where('nama_kandang', $namaKandang)
                ->whereYear('tanggal_produksi', $tahun)
                ->when($bulan, fn($q) => $q->whereMonth('tanggal_produksi', $bulan))
                ->sum('jumlah_gram');
        }

        /*
    |--------------------------------------------------------------------------
    | ROLE LAIN (OWNER / KEPALA KANDANG)
    |--------------------------------------------------------------------------
    */ else {

            $daftarKandang = Kandang::orderBy('nama_kandang')->get();

            $query = Produksi::when($namaKandang, function ($q) use ($namaKandang) {
                $q->where('nama_kandang', $namaKandang);
            })
                ->whereYear('tanggal_produksi', $tahun);

            if ($bulan) {
                $query->whereMonth('tanggal_produksi', $bulan);
            }

            $data_produksi = $query
                ->orderBy('tanggal_produksi', 'desc')
                ->paginate(10)
                ->withQueryString();

            $rataRataProduksi = null;

            if ($namaKandang) {
                $rataRataProduksi = Produksi::where('nama_kandang', $namaKandang)
                    ->whereYear('tanggal_produksi', $tahun)
                    ->when($bulan, fn($q) => $q->whereMonth('tanggal_produksi', $bulan))
                    ->avg('persentase_produksi');
            }

            $totalBeratGram = null;

            if ($namaKandang) {
                $totalBeratGram = Produksi::where('nama_kandang', $namaKandang)
                    ->whereYear('tanggal_produksi', $tahun)
                    ->when($bulan, fn($q) => $q->whereMonth('tanggal_produksi', $bulan))
                    ->sum('jumlah_gram');
            }
        }

        return view('kandang.produksiKandang', [
            'page' => 'Kandang',
            'data_produksi' => $data_produksi,
            'nama_kandang' => $namaKandang,
            'daftarKandang' => $daftarKandang,
            'rataRataProduksi' => $rataRataProduksi,
            'totalBeratGram' => $totalBeratGram ?? 0,
            'bulanSekarang' => $bulanSekarang,
        ]);
    }

    public function cetakLaporan($namaKandang = null)
    {
        $tahun = request()->filled('tahun') ? request('tahun') : now()->year;
        $bulan = request()->filled('bulan') ? request('bulan') : null;

        $query = Produksi::query();

        // Filter kandang jika ada
        if ($namaKandang) {
            $query->where('nama_kandang', $namaKandang);
        }

        // Filter tahun wajib
        $query->whereYear('tanggal_produksi', $tahun);

        // Filter bulan jika ada
        if ($bulan) {
            $query->whereMonth('tanggal_produksi', $bulan);
        }

        // Urutkan tanggal naik
        $query->orderBy('tanggal_produksi', 'asc');

        // Ambil semua data
        $data_produksi = $query->get();

        $nama_kandang_title = $namaKandang ?? 'Semua Kandang';
        $periode = $bulan
            ? Carbon::createFromDate($tahun, $bulan, 1)->translatedFormat('F Y')
            : "Tahun $tahun";

        $tampilkanKandang = is_null($namaKandang);

        // Generate PDF
        $pdf = Pdf::loadView('kandang.produksiLaporanBatch', [
            'data_produksi' => $data_produksi,
            'nama_kandang' => $nama_kandang_title,
            'periode' => $periode,
            'tampilkanKandang' => $tampilkanKandang,
        ])->setPaper('A4', 'landscape');

        // Nama file unik agar browser HP tidak cache PDF lama
        $filename = "laporan-produksi-{$nama_kandang_title}-{$periode}-" . time() . ".pdf";

        return response($pdf->stream($filename))
            ->header('Cache-Control', 'no-cache, no-store, must-revalidate')
            ->header('Pragma', 'no-cache')
            ->header('Expires', '0')
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', "inline; filename=\"$filename\"");
    }







    public function tambahProduksi()
    {

        $user = Auth::user();

        /*
    |--------------------------------------------------------------------------
    | ROLE ANAK KANDANG → HANYA 1 KANDANG
    |--------------------------------------------------------------------------
    */
        if ($user->role === 'anak_kandang') {

            abort_if(!$user->kandang_id, 403);

            $kandang = Kandang::where('id', $user->kandang_id)->get();
        }
        /*
    |--------------------------------------------------------------------------
    | ROLE LAIN (OWNER / KEPALA KANDANG)
    |--------------------------------------------------------------------------
    */ else {

            $kandang = Kandang::orderBy('nama_kandang')->get();
        }

        // Tambahkan data produksi terakhir untuk setiap kandang
        $kandang = $kandang->map(function ($k) {
            $lastProduksi = Produksi::where('nama_kandang', $k->nama_kandang)
                ->orderBy('tanggal_produksi', 'desc')
                ->first();
            $k->lastProduksi = $lastProduksi;
            return $k;
        });

        return view('kandang.produksiTambah', [
            'page' => 'Kandang',
            'data_kandang' => $kandang
        ]);
    }

    public function storeProduksi(Request $request)
    {
        //VALIDASI TAMBAH
        $request->validate([
            'tanggal_produksi' => 'required|date',
            'kandang_id' => 'required|exists:tb_kandang,id',
            'populasi_ayam' => 'required|numeric|min:1',
            'usia' => 'required|numeric|min:0',
            'jenis_telur' => 'required|string',
            'mati' => 'required|numeric|min:0',
            'apkir' => 'required|numeric|min:0',
            'jumlah_butir' => 'required|numeric|min:0',
            'jumlah_pecah' => 'required|numeric|min:0',
            'jumlah_gram' => 'required|numeric|min:0',
            'berat_pakan_per_ayam' => 'required|numeric|min:0',
            'persentase_grower' => 'required|numeric|min:0',
            'persentase_layer' => 'required|numeric|min:0',
            'pakan_A' => 'required|numeric|min:0',
            'pakan_B' => 'required|numeric|min:0',
            'kegiatan' => 'required|string',
            'keterangan' => 'required|string',
        ]);

        try {
            DB::transaction(function () use ($request) {

                // 1️⃣ Ambil data kandang (PASTI ADA)
                $kandang = Kandang::lockForUpdate()->find($request->kandang_id);

                // 2️⃣ Mapping pakan
                $daftarPakan = [
                    'Grower' => $request->pakan_A,
                    'Layer' => $request->pakan_B,
                ];

                foreach ($daftarPakan as $kode => $jumlah) {

                    // 👉 kalau 0, SKIP (INI FIX ERROR KAMU)
                    if ($jumlah <= 0) {
                        continue;
                    }

                    $stokPakan = StokPakan::where('jenis_pakan',  $kode)
                        ->lockForUpdate()
                        ->first();

                    if (!$stokPakan) {
                        throw new \Exception("Stok Pakan $kode tidak ditemukan");
                    }

                    $kandangPakan = KandangPakan::where('kandang_id', $kandang->id)
                        ->where('stok_pakan_id', $stokPakan->id)
                        ->lockForUpdate()
                        ->first();

                    if (!$kandangPakan) {
                        throw new \Exception("Pakan $kode belum terdaftar di kandang");
                    }

                    if ($kandangPakan->stok < $jumlah) {
                        throw new \Exception("Stok Pakan $kode tidak mencukupi");
                    }

                    // 🔽 Kurangi stok
                    $kandangPakan->decrement('stok', $jumlah);
                }

                // 3️⃣ Simpan produksi
                $beratPerAyam = (float) $request->berat_pakan_per_ayam;
                $persentaseGrower = (float) $request->persentase_grower;
                $persentaseLayer = (float) $request->persentase_layer;

                $produksi = Produksi::create([
                    'tanggal_produksi' => $request->tanggal_produksi,
                    'nama_kandang' => $kandang->nama_kandang,
                    'populasi_ayam' => $request->populasi_ayam - ($request->mati + $request->apkir),
                    'usia' => $request->usia,
                    'jenis_telur' => $request->jenis_telur,
                    'apkir' => $request->apkir,
                    'mati' => $request->mati,
                    'jumlah_butir' => $request->jumlah_butir,
                    'jumlah_pecah' => $request->jumlah_pecah,
                    'jumlah_gram' => $request->jumlah_gram,
                    'grower_per_ayam' => $beratPerAyam * ($persentaseGrower / 100),
                    'layer_per_ayam' => $beratPerAyam * ($persentaseLayer / 100),
                    'pakan_A' => $request->pakan_A,
                    'pakan_B' => $request->pakan_B,
                    'persentase_produksi' => ($request->jumlah_butir / $request->populasi_ayam) * 100,
                    'kegiatan' => $request->kegiatan,
                    'keterangan' => $request->keterangan,
                ]);

                // 4️⃣ Gudang masuk telur
                $produksi->gudangMasuk()->create([
                    'tanggal_barang_masuk' => $request->tanggal_produksi,
                    'nama_kandang' => $kandang->nama_kandang,
                    'jenis_telur' => $request->jenis_telur,
                    'jumlah_butir' => $request->jumlah_butir,
                    'jumlah_pecah' => $request->jumlah_pecah,
                    'jumlah_gram' => $request->jumlah_gram,
                ]);

                // 5️⃣ Update populasi ayam
                $kandang->update([
                    'populasi_ayam' => $request->populasi_ayam - ($request->mati + $request->apkir)
                ]);

                // 6️⃣ Tambah stok telur gudang
                $stokGudang = StokTelur::lockForUpdate()
                    ->where('jenis_stok', 'gudang')
                    ->where('jenis_telur', $request->jenis_telur)
                    ->first();

                if (!$stokGudang) {
                    throw new \Exception('Stok telur gudang tidak ditemukan');
                }

                $stokGudang->increment('total_stok', $request->jumlah_gram);
            });

            return redirect('/dashboard/kandang/produksi')->with('messageTambahProduksi', 'Berhasil Tambah Produksi');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', $e->getMessage());
        }
    }

    public function editProduksi($id)
    {
        $user = Auth::user();

        /*
    |--------------------------------------------------------------------------
    | ROLE ANAK KANDANG → HANYA 1 KANDANG
    |--------------------------------------------------------------------------
    */
        if ($user->role === 'anak_kandang') {

            abort_if(!$user->kandang_id, 403);

            $kandang = Kandang::where('id', $user->kandang_id)->get();
        }
        /*
    |--------------------------------------------------------------------------
    | ROLE LAIN (OWNER / KEPALA KANDANG)
    |--------------------------------------------------------------------------
    */ else {

            $kandang = Kandang::orderBy('nama_kandang')->get();
        }
        //mengambil data spesifik dari tabel
        $produksi = produksi::findOrFail($id);

        return view('kandang.produksiEdit', [
            'page' => 'Kandang',
            'data_kandang' => $kandang,
            'data_produksi' => $produksi
        ]);
    }

    public function updateProduksi($id, Request $request)
    {
        $request->validate([
            'tanggal_produksi' => 'required|date',
            'kandang_id' => 'required|exists:tb_kandang,id',
            'populasi_ayam' => 'required|numeric|min:1',
            'usia' => 'required|numeric|min:0',
            'jenis_telur' => 'required|string',
            'mati' => 'required|numeric|min:0',
            'apkir' => 'required|numeric|min:0',
            'jumlah_butir' => 'required|numeric|min:0',
            'jumlah_pecah' => 'required|numeric|min:0',
            'jumlah_gram' => 'required|numeric|min:0',
            'berat_pakan_per_ayam' => 'required|numeric|min:0',
            'persentase_grower' => 'required|numeric|min:0',
            'persentase_layer' => 'required|numeric|min:0',
            'pakan_A' => 'required|numeric|min:0',
            'pakan_B' => 'required|numeric|min:0',
            'kegiatan' => 'required|string',
            'keterangan' => 'required|string',
        ]);

        try {
            DB::transaction(function () use ($request, $id) {

                /* ===============================
             * 1️⃣ AMBIL DATA LAMA
             * =============================== */
                $produksi = Produksi::lockForUpdate()->findOrFail($id);

                $matiLama  = $produksi->mati;
                $apkirLama = $produksi->apkir;
                $totalLama = $matiLama + $apkirLama;

                $jumlahGramLama = $produksi->jumlah_gram;
                $pakanLamaA     = $produksi->pakan_A;
                $pakanLamaB     = $produksi->pakan_B;
                $jenisTelurLama = $produksi->jenis_telur;

                /* ===============================
             * 2️⃣ DATA KANDANG
             * =============================== */
                $kandang = Kandang::lockForUpdate()
                    ->findOrFail($request->kandang_id);

                /* ===============================
             * 3️⃣ FIX POPULASI (PAKAI SELISIH)
             * =============================== */
                $totalBaru = $request->mati + $request->apkir;
                $selisih = $totalBaru - $totalLama;

                if ($selisih > 0) {
                    if ($kandang->populasi_ayam < $selisih) {
                        throw new \Exception('Populasi ayam tidak mencukupi');
                    }
                    $kandang->decrement('populasi_ayam', $selisih);
                } elseif ($selisih < 0) {
                    $kandang->increment('populasi_ayam', abs($selisih));
                }

                /* ===============================
             * 4️⃣ HITUNG ULANG PAKAN
             * =============================== */
                $beratPerAyam = (float) $request->berat_pakan_per_ayam;
                $persentaseGrower = (float) $request->persentase_grower;
                $persentaseLayer = (float) $request->persentase_layer;

                $pakanBaruA = $request->populasi_ayam * ($beratPerAyam * ($persentaseGrower / 100));
                $pakanBaruB = $request->populasi_ayam * ($beratPerAyam * ($persentaseLayer / 100));

                /* ===============================
             * 5️⃣ UPDATE STOK PAKAN
             * =============================== */
                $dataPakan = [
                    'Grower' => ['lama' => $pakanLamaA, 'baru' => $pakanBaruA],
                    'Layer'  => ['lama' => $pakanLamaB, 'baru' => $pakanBaruB],
                ];

                foreach ($dataPakan as $jenis => $nilai) {

                    $selisihPakan = $nilai['baru'] - $nilai['lama'];
                    if ($selisihPakan == 0) continue;

                    $stokPakan = StokPakan::where('jenis_pakan', $jenis)
                        ->lockForUpdate()
                        ->firstOrFail();

                    $kandangPakan = KandangPakan::where('kandang_id', $kandang->id)
                        ->where('stok_pakan_id', $stokPakan->id)
                        ->lockForUpdate()
                        ->firstOrFail();

                    if ($selisihPakan > 0) {
                        if ($kandangPakan->stok < $selisihPakan) {
                            throw new \Exception("Stok pakan $jenis tidak mencukupi");
                        }
                        $kandangPakan->decrement('stok', $selisihPakan);
                    } else {
                        $kandangPakan->increment('stok', abs($selisihPakan));
                    }
                }

                /* ===============================
             * 6️⃣ UPDATE PRODUKSI
             * =============================== */
                $produksi->update([
                    'tanggal_produksi' => $request->tanggal_produksi,
                    'nama_kandang' => $kandang->nama_kandang,
                    'populasi_ayam' => $kandang->populasi_ayam, // ambil dari kandang
                    'usia' => $request->usia,
                    'jenis_telur' => $request->jenis_telur,
                    'apkir' => $request->apkir,
                    'mati' => $request->mati,
                    'jumlah_butir' => $request->jumlah_butir,
                    'jumlah_pecah' => $request->jumlah_pecah,
                    'jumlah_gram' => $request->jumlah_gram,
                    'grower_per_ayam' => $beratPerAyam * ($persentaseGrower / 100),
                    'layer_per_ayam' => $beratPerAyam * ($persentaseLayer / 100),
                    'pakan_A' => $pakanBaruA,
                    'pakan_B' => $pakanBaruB,
                    'persentase_produksi' => ($request->jumlah_butir / max($kandang->populasi_ayam, 1)) * 100,
                    'kegiatan' => $request->kegiatan,
                    'keterangan' => $request->keterangan,
                ]);

                /* ===============================
             * 7️⃣ UPDATE STOK TELUR
             * =============================== */
                $jenisTelurBaru = $request->jenis_telur;
                $jumlahGramBaru = $request->jumlah_gram;

                if ($jenisTelurLama === $jenisTelurBaru) {

                    $stokGudang = StokTelur::lockForUpdate()
                        ->where('jenis_stok', 'gudang')
                        ->where('jenis_telur', $jenisTelurBaru)
                        ->firstOrFail();

                    $stokGudang->increment('total_stok', $jumlahGramBaru - $jumlahGramLama);
                } else {

                    $stokGudangLama = StokTelur::lockForUpdate()
                        ->where('jenis_stok', 'gudang')
                        ->where('jenis_telur', $jenisTelurLama)
                        ->firstOrFail();

                    $stokGudangLama->decrement('total_stok', $jumlahGramLama);

                    $stokGudangBaru = StokTelur::lockForUpdate()
                        ->where('jenis_stok', 'gudang')
                        ->where('jenis_telur', $jenisTelurBaru)
                        ->firstOrFail();

                    $stokGudangBaru->increment('total_stok', $jumlahGramBaru);
                }

                /* ===============================
             * 8️⃣ UPDATE GUDANG MASUK
             * =============================== */
                $produksi->gudangMasuk()->update([
                    'tanggal_barang_masuk' => $request->tanggal_produksi,
                    'nama_kandang' => $kandang->nama_kandang,
                    'jenis_telur' => $jenisTelurBaru,
                    'jumlah_butir' => $request->jumlah_butir,
                    'jumlah_pecah' => $request->jumlah_pecah,
                    'jumlah_gram' => $jumlahGramBaru,
                ]);
            });

            return redirect('/dashboard/kandang/produksi')
                ->with('messageUpdateProduksi', 'Produksi & stok berhasil diupdate');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function destroyProduksi($id)
    {
        try {
            DB::transaction(function () use ($id) {

                /* ===============================
             * 1️⃣ AMBIL DATA PRODUKSI
             * =============================== */
                $produksi = Produksi::lockForUpdate()->findOrFail($id);

                $jumlahGramLama = $produksi->jumlah_gram;
                $pakanLamaA     = $produksi->pakan_A;
                $pakanLamaB     = $produksi->pakan_B;
                $jenisTelur     = $produksi->jenis_telur;

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

                    // ⬆️ kembalikan stok pakan
                    $kandangPakan->increment('stok', $jumlah);
                }

                /* ===============================
             * 4️⃣ KURANGI STOK TELUR GUDANG
             * =============================== */
                $stokGudang = StokTelur::lockForUpdate()
                    ->where('jenis_stok', 'gudang')
                    ->where('jenis_telur', $jenisTelur) // ✅ FIX DI SINI
                    ->firstOrFail();

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

            return redirect('/dashboard/kandang/produksi')
                ->with('messageDeleteProduksi', 'Produksi berhasil dihapus & stok dikembalikan');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }




    /*
    KANDANG
    */
    public function tambahKandang()
    {

        $kandang = kandang::get(); // query ambil data dari database
        return view('kandang.tambahKandang', [
            'page' => 'Kandang',
            'data_kandang' => $kandang
        ]);
    }
    public function storeKandang(Request $request)
    {
        $request->validate([
            'nama_kandang' => 'required|unique:tb_kandang,nama_kandang',
            'chicken_in' => 'required',
            'populasi_ayam' => 'required|numeric',
        ]);

        //tambah data ke tb_kandang
        kandang::create([
            'nama_kandang' => $request->nama_kandang,
            'chicken_in' => $request->chicken_in,
            'populasi_ayam' => $request->populasi_ayam,
        ]);

        return redirect('/dashboard/kandang/tambah-kandang')->with('messageTambahKandang', 'Berhasil Menambahkan Kandang');
    }

    public function updateKandang($id, Request $request)
    {
        $request->validate([
            'nama_kandang' => [
                'required',
                Rule::unique('tb_kandang', 'nama_kandang')->ignore($id),
            ],
            'populasi_ayam' => 'required|numeric',
        ]);

        //tambah data ke tb_kandang
        kandang::where('id', $id)->update([
            'nama_kandang' => $request->nama_kandang,
            'chicken_in' => $request->chicken_in,
            'populasi_ayam' => $request->populasi_ayam,
        ]);

        return back()->with('messageUpdateKandang', 'Data kandang berhasil diupdate');
    }

    //delete data
    public function destroyKandang($id)
    {
        //query hapus data
        kandang::findOrFail($id)->delete();
        return redirect('/dashboard/kandang/tambah-kandang')->with('messageDeleteKandang', 'Berhasil Hapus Kandang');
    }
}
