<?php

namespace App\Http\Controllers;

use App\Models\DistribusiPakan;
use Illuminate\Http\Request;
use App\Models\Kandang;
use App\Models\Produksi;
use App\Models\PakanMasuk;
use App\Models\Saldo;
use App\Models\StokPakan;
use App\Models\KandangPakan;
use Illuminate\Support\Facades\DB;


class PakanController extends Controller
{
    public function pakan()
    {
        $kandangs = Kandang::with([
            'kandangPakan.stokPakan'
        ])
            ->orderBy('nama_kandang')
            ->get();

        

        return view('pakan.pakan', [
            'page' => 'Gudang',
            
            'kandangs' => $kandangs
        ]);
    }

    public function byKandang($kandangId)
    {
        
        $kandang = Kandang::findOrFail($kandangId);
        $data_distribusi = DistribusiPakan::with('stokPakan')
            ->where('kandang_id', $kandangId)
            ->paginate(10);
        return view('pakan.distribusi.DistribusiPakan', [
            'page' => 'Kandang',
            'nama_kandang' => $kandang->nama_kandang,
            'data_distribusi' => $data_distribusi,
            
        ]);
    }

    public function pakanMasuk()
    {
        
        $pakan_masuk = PakanMasuk::orderBy('tanggal_pakan_masuk', 'desc')->get();
        $stokPakanGrower = StokPakan::where('jenis_pakan', 'Grower')->first();
        $stokPakanLayer = StokPakan::where('jenis_pakan', 'Layer')->first();
        $growerKg = $stokPakanGrower
            ? $stokPakanGrower->berat_total / 1000
            : 0;

        $layerKg = $stokPakanLayer
            ? $stokPakanLayer->berat_total / 1000
            : 0;
        return view('pakan.pakanMasuk.pakanMasuk', [
            'page' => 'Pakan',
            
            'stok_pakan_grower' => $growerKg,
            'stok_pakan_layer' => $layerKg,
            'data_pakan' => $pakan_masuk
        ]);
    }

    public function tambahPakanMasuk()
    {
        
        return view('pakan.pakanMasuk.tambahPakanMasuk', [
            'page' => 'Pakan',
            
        ]);
    }

    public function storePakanMasuk(Request $request)
    {
        $request->validate([
            'tanggal_pakan_masuk' => 'required|date',
            'jenis_pakan'         => 'required|string',
            'berat_total'         => 'required|numeric|min:1',
            'harga_kilo'          => 'required|numeric|min:0',
            'total_harga'         => 'required|numeric|min:0',
        ]);

        try {
            DB::transaction(function () use ($request) {

                // SIMPAN PAKAN MASUK
                PakanMasuk::create([
                    'tanggal_pakan_masuk' => $request->tanggal_pakan_masuk,
                    'jenis_pakan'         => $request->jenis_pakan,
                    'berat_total'         => $request->berat_total,
                    'harga_kilo'          => $request->harga_kilo,
                    'total_harga'         => $request->total_harga,
                ]);

                // UPDATE STOK PAKAN
                $stok = StokPakan::where('jenis_pakan', $request->jenis_pakan)
                    ->lockForUpdate()
                    ->first();

                if ($stok) {
                    $stok->increment('berat_total', $request->berat_total);
                } else {
                    StokPakan::create([
                        'jenis_pakan' => $request->jenis_pakan,
                        'berat_total' => $request->berat_total
                    ]);
                }

                // KURANGI SALDO GUDANG
                $saldoGudang = Saldo::where('jenis_saldo', 'gudang')
                    ->lockForUpdate()
                    ->first();

                if (!$saldoGudang) {
                    throw new \Exception('Saldo gudang tidak ditemukan');
                }

                if ($saldoGudang->jumlah_saldo < $request->total_harga) {
                    throw new \Exception('Saldo gudang tidak mencukupi');
                }

                $saldoGudang->decrement('jumlah_saldo', $request->total_harga);
            });

            return redirect('/dashboard/pakan/pakan-masuk')
                ->with('messageTambahPakanMasuk', 'Berhasil Menambahkan Pakan');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', $e->getMessage());
        }
    }


    public function editPakanMasuk($id)
    {
        //mengambil data spesifik dari tabel
        $pakan_masuk = PakanMasuk::findOrFail($id);

        return view('pakan.pakanMasuk.editPakanMasuk', [
            'page' => 'Pakan',
            'data_pakan_masuk' => $pakan_masuk
        ]);
    }

    public function updatePakanMasuk(Request $request, $id)
    {
        $request->validate([
            'tanggal_pakan_masuk' => 'required|date',
            'jenis_pakan'         => 'required|string',
            'berat_total'         => 'required|numeric|min:1',
            'harga_kilo'          => 'required|numeric|min:0',
            'total_harga'         => 'required|numeric|min:0',
        ]);

        try {
            DB::transaction(function () use ($request, $id) {

                // =========================
                // 1. AMBIL DATA LAMA (LOCK)
                // =========================
                $pakan = PakanMasuk::lockForUpdate()->findOrFail($id);

                $jenisLama  = $pakan->jenis_pakan;
                $beratLama  = $pakan->berat_total;
                $totalLama  = $pakan->total_harga;

                // =========================
                // 2. UPDATE DATA PAKAN
                // =========================
                $pakan->update([
                    'tanggal_pakan_masuk' => $request->tanggal_pakan_masuk,
                    'jenis_pakan'         => $request->jenis_pakan,
                    'berat_total'         => $request->berat_total,
                    'harga_kilo'          => $request->harga_kilo,
                    'total_harga'         => $request->total_harga,
                ]);

                // =========================
                // 3. UPDATE STOK PAKAN
                // =========================
                if ($jenisLama === $request->jenis_pakan) {

                    $selisih = $request->berat_total - $beratLama;

                    StokPakan::where('jenis_pakan', $request->jenis_pakan)
                        ->lockForUpdate()
                        ->increment('berat_total', $selisih);
                } else {

                    // kurangi stok lama
                    StokPakan::where('jenis_pakan', $jenisLama)
                        ->lockForUpdate()
                        ->decrement('berat_total', $beratLama);

                    // tambah stok baru
                    StokPakan::firstOrCreate(
                        ['jenis_pakan' => $request->jenis_pakan],
                        ['berat_total' => 0]
                    )->increment('berat_total', $request->berat_total);
                }

                // =========================
                // 4. UPDATE SALDO GUDANG
                // =========================
                $saldoGudang = Saldo::where('jenis_saldo', 'gudang')
                    ->lockForUpdate()
                    ->first();

                if (!$saldoGudang) {
                    throw new \Exception('Saldo gudang tidak ditemukan');
                }

                // selisih harga (positif = tambah potongan, negatif = saldo kembali)
                $selisihHarga = $request->total_harga - $totalLama;

                if ($saldoGudang->jumlah_saldo < $selisihHarga && $selisihHarga > 0) {
                    throw new \Exception('Saldo gudang tidak mencukupi');
                }

                // potong / kembalikan saldo
                $saldoGudang->jumlah_saldo -= $selisihHarga;
                $saldoGudang->save();
            });

            return redirect('/dashboard/pakan/pakan-masuk')
                ->with('messageUpdatePakanMasuk', 'Berhasil Update Pakan');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', $e->getMessage());
        }
    }

    public function destroyPakanMasuk($id)
    {
        try {
            DB::transaction(function () use ($id) {

                // =========================
                // 1. AMBIL DATA PAKAN (LOCK)
                // =========================
                $pakan = PakanMasuk::lockForUpdate()->findOrFail($id);

                // =========================
                // 2. KEMBALIKAN STOK PAKAN
                // =========================
                StokPakan::where('jenis_pakan', $pakan->jenis_pakan)
                    ->lockForUpdate()
                    ->decrement('berat_total', $pakan->berat_total);

                // =========================
                // 3. KEMBALIKAN SALDO GUDANG
                // =========================
                $saldoGudang = Saldo::where('jenis_saldo', 'gudang')
                    ->lockForUpdate()
                    ->first();

                if (!$saldoGudang) {
                    throw new \Exception('Saldo gudang tidak ditemukan');
                }

                $saldoGudang->increment('jumlah_saldo', $pakan->total_harga);

                // =========================
                // 4. HAPUS DATA PAKAN MASUK
                // =========================
                $pakan->delete();
            });

            return redirect('/dashboard/pakan/pakan-masuk')
                ->with('messageDeletePakanMasuk', 'Berhasil Menghapus Pakan Masuk');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', $e->getMessage());
        }
    }


    /*
    DISTRIBUSI PAKAN
    */
    public function distribusiPakan()
    {
        
        $distribusi_pakan = DistribusiPakan::with(['kandang', 'stokPakan'])
            ->orderBy('tanggal_distribusi', 'desc')
            ->paginate(10);
        $stokPakanGrower = StokPakan::where('jenis_pakan', 'Grower')->first();
        $stokPakanLayer = StokPakan::where('jenis_pakan', 'Layer')->first();
        $growerKg = $stokPakanGrower
            ? $stokPakanGrower->berat_total / 1000
            : 0;

        $layerKg = $stokPakanLayer
            ? $stokPakanLayer->berat_total / 1000
            : 0;
        return view('pakan.distribusi.distribusiPakan', [
            'page' => 'Pakan',
            'stok_pakan_grower' => $growerKg,
            'stok_pakan_layer' => $layerKg,
            'data_distribusi' => $distribusi_pakan
        ]);
    }

    public function tambahDistribusiPakan()
    {
        
        $kandang = kandang::get();
        $pakan = StokPakan::get();
        return view('pakan.distribusi.tambahDistribusiPakan', [
            'page' => 'Pakan',
            
            'data_kandang' => $kandang,
            'data_pakan' => $pakan
        ]);
    }

    public function storeDistribusiPakan(Request $request)
    {
        $request->validate([
            'tanggal_distribusi' => 'required|date',
            'kandang_id' => 'required|exists:tb_kandang,id',
            'stok_pakan_id' => 'required|exists:tb_stok_pakan,id',
            'jumlah_berat' => 'required|numeric|min:1',
        ]);

        try {
            DB::transaction(function () use ($request) {

                $stokGudang = StokPakan::where('id', $request->stok_pakan_id)
                    ->lockForUpdate()
                    ->firstOrFail();

                // 🔴 CEK STOK
                if ($stokGudang->berat_total < $request->jumlah_berat) {
                    throw new \Exception('Stok pakan tidak mencukupi');
                }

                $stokGudang->decrement('berat_total', $request->jumlah_berat);

                $kandangPakan = KandangPakan::firstOrCreate(
                    [
                        'kandang_id' => $request->kandang_id,
                        'stok_pakan_id' => $stokGudang->id
                    ],
                    ['stok' => 0]
                );

                $kandangPakan->increment('stok', $request->jumlah_berat);

                DistribusiPakan::create([
                    'tanggal_distribusi' => $request->tanggal_distribusi,
                    'kandang_id' => $request->kandang_id,
                    'stok_pakan_id' => $stokGudang->id,
                    'jumlah_berat' => $request->jumlah_berat
                ]);
            });
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->withErrors(['stok' => $e->getMessage()]);
        }

        return redirect('/dashboard/pakan/distribusi')
            ->with('messageTambahDistribusiPakan', 'Distribusi pakan berhasil');
    }

    public function editDistribusiPakan($id)
    {
        //mengambil data spesifik dari tabel
        $distribusi_pakan = DistribusiPakan::findOrFail($id);
        $kandang = kandang::get();
        $pakan = StokPakan::get();
        return view('pakan.distribusi.editDistribusiPakan', [
            'page' => 'Pakan',
            'data_kandang' => $kandang,
            'data_pakan' => $pakan,
            'data_distribusi' => $distribusi_pakan
        ]);
    }

    public function updateDistribusiPakan(Request $request, $id)
    {
        $request->validate([
            'tanggal_distribusi' => 'required|date',
            'kandang_id' => 'required|exists:tb_kandang,id',
            'stok_pakan_id' => 'required|exists:tb_stok_pakan,id',
            'jumlah_berat' => 'required|numeric|min:1',
        ]);

        try {
            DB::transaction(function () use ($request, $id) {

                $distribusi = DistribusiPakan::lockForUpdate()->findOrFail($id);

                $oldKandang = $distribusi->kandang_id;
                $oldPakan   = $distribusi->stok_pakan_id;
                $oldJumlah  = $distribusi->jumlah_berat;

                $newKandang = $request->kandang_id;
                $newPakan   = $request->stok_pakan_id;
                $newJumlah  = $request->jumlah_berat;

                /*
            |==================================================
            | 1️⃣ ROLLBACK DATA LAMA (WAJIB)
            |==================================================
            */

                // rollback stok kandang lama
                $stokKandangLama = KandangPakan::where('kandang_id', $oldKandang)
                    ->where('stok_pakan_id', $oldPakan)
                    ->lockForUpdate()
                    ->firstOrFail();

                if ($stokKandangLama->stok < $oldJumlah) {
                    throw new \Exception('Stok kandang lama tidak mencukupi');
                }

                $stokKandangLama->decrement('stok', $oldJumlah);

                // rollback ke gudang lama
                $stokGudangLama = StokPakan::lockForUpdate()->findOrFail($oldPakan);
                $stokGudangLama->increment('berat_total', $oldJumlah);

                /*
            |==================================================
            | 2️⃣ TERAPKAN DATA BARU
            |==================================================
            */

                $stokGudangBaru = StokPakan::lockForUpdate()->findOrFail($newPakan);

                if ($stokGudangBaru->berat_total < $newJumlah) {
                    throw new \Exception('Stok gudang tidak mencukupi');
                }

                $stokGudangBaru->decrement('berat_total', $newJumlah);

                $stokKandangBaru = KandangPakan::firstOrCreate(
                    [
                        'kandang_id' => $newKandang,
                        'stok_pakan_id' => $newPakan
                    ],
                    ['stok' => 0]
                );

                $stokKandangBaru->increment('stok', $newJumlah);

                /*
            |==================================================
            | 3️⃣ UPDATE DATA DISTRIBUSI
            |==================================================
            */
                $distribusi->update([
                    'tanggal_distribusi' => $request->tanggal_distribusi,
                    'kandang_id' => $newKandang,
                    'stok_pakan_id' => $newPakan,
                    'jumlah_berat' => $newJumlah,
                ]);
            });

            return redirect('/dashboard/pakan/distribusi')
                ->with('messageUpdateDistribusiPakan', 'Distribusi pakan berhasil diperbarui');
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->withErrors(['stok' => $e->getMessage()]);
        }
    }

    public function destroyDistribusiPakan($id)
    {
        try {
            DB::transaction(function () use ($id) {

                // 1️⃣ Ambil distribusi
                $distribusi = DistribusiPakan::lockForUpdate()->findOrFail($id);

                $jumlah = $distribusi->jumlah_berat;

                // 2️⃣ Ambil stok kandang
                $stokKandang = KandangPakan::where('kandang_id', $distribusi->kandang_id)
                    ->where('stok_pakan_id', $distribusi->stok_pakan_id)
                    ->lockForUpdate()
                    ->firstOrFail();

                // 3️⃣ Kurangi stok kandang
                if ($stokKandang->stok < $jumlah) {
                    throw new \Exception('Stok kandang tidak mencukupi untuk rollback distribusi');
                }
                $stokKandang->decrement('stok', $jumlah);

                // 4️⃣ Tambahkan kembali ke stok gudang
                $stokGudang = StokPakan::lockForUpdate()->findOrFail($distribusi->stok_pakan_id);
                $stokGudang->increment('berat_total', $jumlah);

                // 5️⃣ Hapus distribusi
                $distribusi->delete();
            });

            return redirect('/dashboard/pakan/distribusi')
                ->with('messageDeleteDistribusiPakan', 'Distribusi pakan berhasil dihapus');
        } catch (\Exception $e) {
            return back()
                ->withErrors(['stok' => $e->getMessage()]);
        }
    }

    public function updateStok(Request $request)
    {
        $request->validate([
            'jenis_pakan' => 'required|in:grower,layer',
            'berat_total' => 'required|numeric|min:0',
        ]);

        StokPakan::where('jenis_pakan', $request->jenis_pakan)
            ->update([
                'berat_total' => ($request->berat_total * 1000)
            ]);

        return back()->with('success', 'Stok pakan berhasil diperbarui');
    }
}
