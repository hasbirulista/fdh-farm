<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EggGrowBarangMasuk;
use App\Models\PengeluaranToko;
use App\Models\Kandang;
use App\Models\Produksi;
use App\Models\StokTelur;
use App\Models\Saldo;
use App\Models\Pelanggan;
use App\Models\Transaksi;
use App\Models\PakanMasuk;
use App\Models\StokPakan;
use App\Models\KandangPakan;
use App\Models\DistribusiPakan;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class EggGrowController extends Controller
{
    public function dashboard()
    {
        // ===== SALDO TOKO =====
        $saldoToko = Saldo::where('jenis_saldo', 'toko')->first();
        $saldoTokoValue = $saldoToko ? $saldoToko->jumlah_saldo : 0;

        // ===== STOK TELUR TOKO =====
        $stokTelurOmegaToko = StokTelur::where('jenis_stok', 'toko')
            ->where('jenis_telur', 'Omega')
            ->first();
        $stokTelurBiasaToko = StokTelur::where('jenis_stok', 'toko')
            ->where('jenis_telur', 'Biasa')
            ->first();

        // ===== AMBIL GRAM ASLI =====
        $omegaGram = $stokTelurOmegaToko ? $stokTelurOmegaToko->total_stok : 0;
        $biasaGram = $stokTelurBiasaToko ? $stokTelurBiasaToko->total_stok : 0;

        // ===== KONVERSI KE KG UNTUK TAMPILAN =====
        $omegaKg = $omegaGram / 1000;
        $biasaKg = $biasaGram / 1000;

        // ===== HITUNG PROFIT HARI INI =====
        $hariIni = Carbon::today();
        $transaksiHariIni = Transaksi::whereDate('tanggal_transaksi', $hariIni)->get();

        $profitHariIni = 0;
        foreach ($transaksiHariIni as $transaksi) {
            // Hitung profit manual
            $hargaBeli = ($transaksi->total_berat / 1000) * $transaksi->harga_beli_kilo;
            $hargaJual = $transaksi->total_harga;
            $profitHariIni += ($hargaJual - $hargaBeli);
        }

        // ===== KIRIM DATA KE VIEW =====
        return view('egg-grow.dashboard', [
            'page' => 'Egg Grow',

            // UNTUK CARD (KG)
            'stok_telur_omega_toko_view' => number_format($omegaKg, 2, ',', '.'),
            'stok_telur_biasa_toko_view' => number_format($biasaKg, 2, ',', '.'),

            // UNTUK MODAL (GRAM ASLI, TANPA FORMAT)
            'stok_telur_omega_toko_raw' => $omegaGram,
            'stok_telur_biasa_toko_raw' => $biasaGram,

            'saldo_toko' => $saldoTokoValue,
            'profit_hari_ini' => $profitHariIni,
        ]);
    }

    public function updateStokTelur(Request $request)
    {
        $request->validate([
            'jenis_telur' => 'required|in:Omega,Biasa', // sesuai value Blade
            'jenis_stok'  => 'required|in:toko',       // ini sudah benar
            'total_stok'  => 'required|numeric|min:0',
        ]);

        // update stok
        StokTelur::where('jenis_telur', $request->jenis_telur)
            ->where('jenis_stok', $request->jenis_stok)
            ->update([
                'total_stok' => $request->total_stok,
            ]);

        return back()->with('success', 'Stok berhasil diperbarui');
    }



    public function profitByDate(Request $request)
    {
        $tanggal = $request->tanggal;

        if (!$tanggal) {
            return response()->json(['profit' => '0']);
        }

        $profit = Transaksi::whereDate('tanggal_transaksi', $tanggal)
            ->selectRaw('SUM(total_harga - (harga_beli_kilo * total_berat / 1000)) as profit')
            ->value('profit');

        return response()->json([
            'profit' => number_format($profit ?? 0, 0, ',', '.')
        ]);
    }



    public function barangMasuk()
    {
        $bulan = request()->has('bulan')
            ? request('bulan')   // bisa null / kosong
            : now()->month;      // default bulan sekarang

        $tahun = request()->filled('tahun')
            ? request('tahun')
            : now()->year;

        $query = EggGrowBarangMasuk::whereYear('tanggal_barang_masuk', $tahun);

        // ⬇️ HANYA filter bulan jika dipilih
        if (!empty($bulan)) {
            $query->whereMonth('tanggal_barang_masuk', $bulan);
        }

        $data_barang_masuk = $query
            ->orderBy('tanggal_barang_masuk', 'desc')
            ->paginate(10);

        return view('egg-grow.barangMasuk.eggGrowBarangMasuk', [
            'page' => 'Egg Grow',
            'bulan' => $bulan,
            'tahun' => $tahun,
            'data_barang_masuk' => $data_barang_masuk
        ]);
    }


    public function cetakBarangMasuk(Request $request)
    {
        $bulan = $request->filled('bulan') ? $request->bulan : null;
        $tahun = $request->filled('tahun') ? $request->tahun : now()->year;

        $query = EggGrowBarangMasuk::query();
        $query->whereYear('tanggal_barang_masuk', $tahun);

        if ($bulan) {
            $query->whereMonth('tanggal_barang_masuk', $bulan);
        }

        $data_barang_masuk = $query->orderBy('tanggal_barang_masuk', 'asc')->get();

        $periode = $bulan
            ? \Carbon\Carbon::createFromDate($tahun, $bulan, 1)->translatedFormat('F Y')
            : "Tahun $tahun";

        // Load PDF
        $pdf = Pdf::loadView('egg-grow.barangMasuk.barangMasukLaporan', [
            'data_barang_masuk' => $data_barang_masuk,
            'periode' => $periode
        ])->setPaper('A4', 'potrait');

        // Nama file unik untuk mencegah cache HP
        $filename = "laporan-barang-masuk-$periode-" . time() . ".pdf";

        return response($pdf->stream($filename))
            ->header('Cache-Control', 'no-cache, no-store, must-revalidate')
            ->header('Pragma', 'no-cache')
            ->header('Expires', '0')
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', "inline; filename=\"$filename\"");
    }





    /*
    PELANGGAN
    */

    /**
     * List pelanggan
     */
    public function pelanggan(Request $request)
    {
        $query = Pelanggan::query();

        // 🔍 Search nama / no hp
        if ($request->filled('q')) {
            $query->where(function ($q) use ($request) {
                $q->where('nama_pelanggan', 'like', '%' . $request->q . '%')
                    ->orWhere('no_hp', 'like', '%' . $request->q . '%');
            });
        }

        // 🔁 Filter repeat order
        if ($request->filled('repeat')) {
            $query->where('repeat_order_aktif', $request->repeat);
        }

        $pelanggans = $query
            ->orderBy('nama_pelanggan')
            ->paginate(10)
            ->withQueryString(); // ⬅ penting agar filter tidak hilang saat pindah halaman

        return view('egg-grow.pelanggan.pelanggan', [
            'page' => 'Egg Grow',
            'pelanggans' => $pelanggans
        ]);
    }

    public function cetakPelanggan()
    {
        $data_pelanggan = Pelanggan::orderBy('nama_pelanggan')->get();

        $pdf = Pdf::loadView('egg-grow.pelanggan.pelangganLaporan', [
            'data_pelanggan' => $data_pelanggan,
            'periode' => 'Semua Pelanggan'
        ])->setPaper('A4', 'potrait');

        return $pdf->stream('laporan-semua-pelanggan.pdf');
    }



    /**
     * Form tambah pelanggan
     */
    public function tambahPelanggan()
    {
        return view('egg-grow.pelanggan.tambahPelanggan', [
            'page' => 'Egg Grow'
        ]);
    }

    /**
     * Simpan pelanggan
     */
    public function storePelanggan(Request $request)
    {
        $request->validate([
            'nama_pelanggan' => 'required|string|max:255',
            'no_hp' => 'required|string',
            'alamat' => 'required|string',
            'repeat_order_aktif' => 'required|in:1,0', // HARUS 1/0
            'repeat_order_hari' => 'nullable|integer|min:1'
        ]);

        $repeat_aktif = $request->repeat_order_aktif; // sudah 1 atau 0 dari select

        Pelanggan::create([
            'nama_pelanggan' => $request->nama_pelanggan,
            'no_hp' => $request->no_hp,
            'alamat' => $request->alamat,
            'repeat_order_aktif' => $repeat_aktif,
            'repeat_order_hari' => $repeat_aktif == 1 ? $request->repeat_order_hari : null,
        ]);

        return redirect('/dashboard/egg-grow/pelanggan')->with('messageTambahPelanggan', 'Berhasil Tambah Pelanggan');
    }

    /**
     * Form edit pelanggan
     */
    public function editPelanggan($id)
    {
        $pelanggan = Pelanggan::findOrFail($id);
        return view('egg-grow.pelanggan.editPelanggan', compact('pelanggan'), [
            'page' => 'Egg Grow'
        ]);
    }

    /**
     * Update pelanggan
     */
    public function updatePelanggan(Request $request, $id)
    {
        $request->validate([
            'nama_pelanggan' => 'required|string|max:255',
            'no_hp' => 'required|string',
            'alamat' => 'required|string',
            'repeat_order_aktif' => 'required|in:1,0', // HARUS 1/0
            'repeat_order_hari' => 'nullable|integer|min:1'
        ]);

        $pelanggan = Pelanggan::findOrFail($id);
        $repeat_aktif = $request->repeat_order_aktif; // sudah 1 atau 0 dari select

        $pelanggan->update([
            'nama_pelanggan' => $request->nama_pelanggan,
            'no_hp' => $request->no_hp,
            'alamat' => $request->alamat,
            'repeat_order_aktif' => $request->has('repeat_order_aktif'),
            'repeat_order_aktif' => $repeat_aktif,
            'repeat_order_hari' => $repeat_aktif == 1 ? $request->repeat_order_hari : null,
        ]);

        return redirect('/dashboard/egg-grow/pelanggan')->with('messageUpdatePelanggan', 'Berhasil Update Pelanggan');
    }

    /**
     * Hapus pelanggan
     */
    public function destroyPelanggan($id)
    {
        Pelanggan::findOrFail($id)->delete();
        return redirect('/dashboard/egg-grow/pelanggan')->with('messageDeletePelanggan', 'Berhasil Hapus Pelanggan');
    }

    /*
    TRANSAKSI PENJUALAN
    */
    public function transaksi()
    {
        // Kalau tidak ada di URL → pakai bulan & tahun saat ini
        $bulan = request()->has('bulan')
            ? request('bulan')
            : now()->month;

        $tahun = request()->has('tahun')
            ? request('tahun')
            : now()->year;

        $query = Transaksi::with('pelanggan');

        // Filter tahun (wajib)
        $query->whereYear('tanggal_transaksi', $tahun);

        // Filter bulan (default = bulan sekarang)
        if ($bulan !== 'all') {
            $query->whereMonth('tanggal_transaksi', $bulan);
        }

        $transaksis = $query
            ->orderBy('tanggal_transaksi', 'desc')
            ->paginate(10)
            ->appends(request()->query());

        return view('egg-grow.transaksi.transaksi', [
            'page' => 'Egg Grow',
            'transaksis' => $transaksis,
            'bulan' => $bulan,
            'tahun' => $tahun,
        ]);
    }


    public function cetakTransaksi(Request $request)
    {
        $bulan = $request->get('bulan');
        $tahun = $request->get('tahun', now()->year);

        // Ambil data terbaru dari database
        $query = Transaksi::with('pelanggan')
            ->whereYear('tanggal_transaksi', $tahun);

        if ($bulan && $bulan !== 'all') {
            $query->whereMonth('tanggal_transaksi', $bulan);
        }

        $data = $query->orderBy('tanggal_transaksi', 'asc')->get();

        // Hitung total omzet dan profit
        $totalOmzet = $data->sum('total_harga');

        $totalProfit = $data->sum(function ($item) {
            return $item->total_harga - ($item->harga_beli_kilo * ($item->total_berat / 1000));
        });

        // Tentukan periode laporan
        if ($bulan && $bulan !== 'all') {
            $periode = Carbon::createFromDate($tahun, $bulan, 1)->translatedFormat('F Y');
        } else {
            $periode = "Tahun $tahun";
        }

        // Generate PDF
        $pdf = PDF::loadView('egg-grow.transaksi.transaksiLaporan', [
            'data' => $data,
            'periode' => $periode,
            'totalOmzet' => $totalOmzet,
            'totalProfit' => $totalProfit,
        ])->setPaper('A4', 'landscape');

        // Nama file unik agar browser HP tidak men-cache PDF lama
        $filename = "laporan-transaksi-{$periode}-" . time() . ".pdf";

        // Kembalikan PDF dengan header no-cache
        return response($pdf->stream($filename))
            ->header('Cache-Control', 'no-cache, no-store, must-revalidate')
            ->header('Pragma', 'no-cache')
            ->header('Expires', '0')
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', "inline; filename=\"$filename\"");
    }





    public function tambahTransaksi()
    {
        $pelanggans = Pelanggan::all();
        return view('egg-grow.transaksi.tambahTransaksi', compact('pelanggans'), [
            'page' => 'Egg Grow'

        ]);
    }

    public function storeTransaksi(Request $request)
    {
        $request->validate([
            'pelanggan_id' => 'required|exists:tb_pelanggan,id',
            'tanggal_transaksi' => 'required|date',
            'jenis_telur' => 'required|string',
            'total_berat' => 'required|integer|min:1',
            'harga_beli_kilo' => 'required|integer|min:1',
            'harga_jual_kilo' => 'required|integer|min:1',
            'total_harga' => 'required|integer|min:1',
            'pembayaran' => 'required|string',
        ]);

        try {
            DB::transaction(function () use ($request) {

                // 1️⃣ Ambil stok telur TOKO
                $stokTelur = StokTelur::where('jenis_stok', 'toko')
                    ->where('jenis_telur', $request->jenis_telur)
                    ->lockForUpdate()
                    ->first();

                if (!$stokTelur) {
                    throw ValidationException::withMessages([
                        'jenis_telur' => 'Stok telur jenis ' . $request->jenis_telur . ' tidak tersedia di toko.'
                    ]);
                }

                if ($stokTelur->total_stok < $request->total_berat) {
                    throw ValidationException::withMessages([
                        'total_berat' => 'Stok telur tidak mencukupi. Sisa stok telur (' . $stokTelur->jenis_telur . ') : ' . $stokTelur->total_stok . ' gram.'
                    ]);
                }

                // 2️⃣ Simpan transaksi
                Transaksi::create([
                    'pelanggan_id' => $request->pelanggan_id,
                    'tanggal_transaksi' => $request->tanggal_transaksi,
                    'jenis_telur' => $request->jenis_telur,
                    'total_berat' => $request->total_berat,
                    'harga_beli_kilo' => $request->harga_beli_kilo,
                    'harga_jual_kilo' => $request->harga_jual_kilo,
                    'total_harga' => ($request->total_berat / 1000) * $request->harga_jual_kilo,
                    'pembayaran' => $request->pembayaran
                ]);

                // 3️⃣ Kurangi stok telur
                $stokTelur->decrement('total_stok', $request->total_berat);

                // 4️⃣ Update saldo TOKO
                $saldo = Saldo::where('jenis_saldo', 'toko')
                    ->lockForUpdate()
                    ->first();

                if (!$saldo) {
                    throw ValidationException::withMessages([
                        'jumlah_saldo' => 'Saldo toko belum tersedia.'
                    ]);
                }

                $saldo->increment('jumlah_saldo', $request->total_harga);
            });
        } catch (ValidationException $e) {
            return back()
                ->withErrors($e->errors())
                ->withInput();
        } catch (\Exception $e) {
            dd($e->getMessage());
        }

        return redirect('/dashboard/egg-grow/transaksi')
            ->with('messageTambahTransaksi', 'Berhasil Menambah Transaksi');
    }

    public function editTransaksi($id)
    {
        $pelanggans = Pelanggan::all();
        $transaksi = Transaksi::find($id);
        return view('egg-grow.transaksi.editTransaksi', compact('transaksi', 'pelanggans'), [
            'page' => 'Egg Grow'
        ]);
    }

    public function updateTransaksi(Request $request, $id)
    {
        $request->validate([
            'pelanggan_id' => 'required|exists:tb_pelanggan,id',
            'tanggal_transaksi' => 'required|date',
            'jenis_telur' => 'required|string',
            'total_berat' => 'required|integer|min:1',
            'harga_beli_kilo' => 'required|integer|min:1',
            'harga_jual_kilo' => 'required|integer|min:1',
            'total_harga' => 'required|integer|min:1',
            'pembayaran' => 'required|string',
        ]);

        try {
            DB::transaction(function () use ($request, $id) {

                // 1️⃣ Ambil transaksi lama
                $transaksi = Transaksi::lockForUpdate()->findOrFail($id);

                // 2️⃣ Ambil stok telur LAMA
                $stokTelurLama = StokTelur::where('jenis_stok', 'toko')
                    ->where('jenis_telur', $transaksi->jenis_telur)
                    ->lockForUpdate()
                    ->first();

                if (!$stokTelurLama) {
                    throw ValidationException::withMessages([
                        'jenis_telur' => 'Stok telur lama tidak ditemukan.'
                    ]);
                }

                // 3️⃣ Kembalikan stok & saldo lama
                $stokTelurLama->increment('total_stok', $transaksi->total_berat);

                $saldo = Saldo::where('jenis_saldo', 'toko')
                    ->lockForUpdate()
                    ->first();

                if (!$saldo) {
                    throw ValidationException::withMessages([
                        'jumlah_saldo' => 'Saldo toko belum tersedia.'
                    ]);
                }

                $saldo->decrement('jumlah_saldo', $transaksi->total_harga);

                // 4️⃣ Ambil stok telur BARU
                $stokTelurBaru = StokTelur::where('jenis_stok', 'toko')
                    ->where('jenis_telur', $request->jenis_telur)
                    ->lockForUpdate()
                    ->first();

                if (!$stokTelurBaru) {
                    throw ValidationException::withMessages([
                        'jenis_telur' => 'Stok telur jenis ' . $request->jenis_telur . ' tidak tersedia.'
                    ]);
                }

                if ($stokTelurBaru->total_stok < $request->total_berat) {
                    throw ValidationException::withMessages([
                        'total_berat' => 'Stok telur tidak mencukupi. Sisa stok telur (' .
                            $stokTelurBaru->jenis_telur . ') : ' . $stokTelurBaru->total_stok . ' gram.'
                    ]);
                }

                // 5️⃣ Update transaksi
                $transaksi->update([
                    'pelanggan_id' => $request->pelanggan_id,
                    'tanggal_transaksi' => $request->tanggal_transaksi,
                    'jenis_telur' => $request->jenis_telur,
                    'total_berat' => $request->total_berat,
                    'harga_beli_kilo' => $request->harga_beli_kilo,
                    'harga_jual_kilo' => $request->harga_jual_kilo,
                    'total_harga' => ($request->total_berat / 1000) * $request->harga_jual_kilo,
                    'pembayaran' => $request->pembayaran
                ]);

                // 6️⃣ Kurangi stok & tambah saldo BARU
                $stokTelurBaru->decrement('total_stok', $request->total_berat);
                $saldo->increment('jumlah_saldo', $request->total_harga);
            });
        } catch (ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            return back()
                ->with('error', 'Terjadi kesalahan sistem saat update transaksi.')
                ->withInput();
        }

        return redirect('/dashboard/egg-grow/transaksi')
            ->with('messageUpdateTransaksi', 'Berhasil Update Transaksi');
    }

    public function destroyTransaksi($id)
    {
        try {
            DB::transaction(function () use ($id) {

                // 1️⃣ Ambil transaksi
                $transaksi = Transaksi::lockForUpdate()->findOrFail($id);

                // 2️⃣ Kembalikan stok telur
                $stokTelur = StokTelur::where('jenis_stok', 'toko')
                    ->where('jenis_telur', $transaksi->jenis_telur)
                    ->lockForUpdate()
                    ->first();

                if ($stokTelur) {
                    $stokTelur->increment('total_stok', $transaksi->total_berat);
                }

                // 3️⃣ Kurangi saldo toko
                $saldo = Saldo::where('jenis_saldo', 'toko')
                    ->lockForUpdate()
                    ->first();

                if ($saldo) {
                    $saldo->decrement('jumlah_saldo', $transaksi->total_harga);
                }

                // 4️⃣ Hapus transaksi
                $transaksi->delete();
            });
        } catch (\Exception $e) {
            return back()
                ->with('error', 'Gagal menghapus transaksi.');
        }

        return redirect('/dashboard/egg-grow/transaksi')
            ->with('messageDeleteTransaksi', 'Transaksi berhasil dihapus');
    }


    public function rankingPelanggan(Request $request)
    {
        $query = DB::table('tb_transaksi')
            ->select(
                'pelanggan.nama_pelanggan',
                DB::raw('COUNT(*) as jumlah_transaksi'),
                DB::raw('SUM(CASE WHEN jenis_telur = "Omega" THEN total_berat ELSE 0 END) as total_omega'),
                DB::raw('SUM(CASE WHEN jenis_telur = "Biasa" THEN total_berat ELSE 0 END) as total_biasa'),
                DB::raw('SUM(total_berat) as total_berat')
            )
            ->join('tb_pelanggan as pelanggan', 'tb_transaksi.pelanggan_id', '=', 'pelanggan.id');

        // Filter tanggal
        if ($request->filled('start_date')) {
            $query->whereDate('tanggal_transaksi', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('tanggal_transaksi', '<=', $request->end_date);
        }

        $ranking = $query->groupBy('pelanggan.nama_pelanggan')
            ->orderByDesc('total_berat')
            ->get();

        return view('egg-grow.transaksi.pelanggan-ranking', compact('ranking'), [
            'page' => 'Egg Grow'
        ]);
    }


    public function pengeluaran(Request $request)
    {
        // Default bulan ke bulan saat ini, tahun ke tahun saat ini
        $bulan = $request->input('bulan', now()->month);
        $tahun = $request->input('tahun', now()->year);

        $query = PengeluaranToko::query();

        // Filter TAHUN (wajib)
        $query->whereYear('tanggal', $tahun);

        // Filter BULAN (kecuali jika 'all')
        if ($bulan !== 'all') {
            $query->whereMonth('tanggal', $bulan);
        }

        // Urut terbaru
        $query->orderBy('tanggal', 'desc');

        // Paginate
        $data_pengeluaran = $query->paginate(10)->appends(compact('bulan', 'tahun'));

        return view('egg-grow.pengeluaran.pengeluaran', [
            'page' => 'Egg Grow',
            'data_pengeluaran' => $data_pengeluaran,
            'bulan' => $bulan,
            'tahun' => $tahun
        ]);
    }

    // Fungsi cetak PDF
    public function cetakPengeluaran(Request $request)
    {
        $query = PengeluaranToko::query();

        // Filter BULAN
        if ($request->filled('bulan') && $request->bulan !== 'all') {
            $query->whereMonth('tanggal', $request->bulan);
        }

        // Filter TAHUN
        if ($request->filled('tahun')) {
            $query->whereYear('tanggal', $request->tahun);
        } else {
            $query->whereYear('tanggal', now()->year);
        }

        // Urut terbaru
        $query->orderBy('tanggal', 'desc');

        $data_pengeluaran = $query->get();

        // Tentukan periode
        if ($request->filled('bulan') && $request->bulan !== 'all') {
            $periode = Carbon::createFromDate($request->tahun, $request->bulan, 1)
                ->translatedFormat('F Y');
        } else {
            $periode = "Tahun " . ($request->tahun ?? now()->year);
        }

        // Load PDF
        $pdf = PDF::loadView('egg-grow.pengeluaran.pengeluaranLaporan', [
            'data_pengeluaran' => $data_pengeluaran,
            'periode' => $periode
        ])->setPaper('A4', 'portrait');

        // Nama file unik → supaya HP tidak cache PDF lama
        $filename = "laporan-pengeluaran-{$periode}-" . time() . ".pdf";

        return response($pdf->stream($filename))
            ->header('Cache-Control', 'no-cache, no-store, must-revalidate')
            ->header('Pragma', 'no-cache')
            ->header('Expires', '0')
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', "inline; filename=\"$filename\"");
    }



    public function tambahPengeluaran()
    {
        return view('egg-grow.pengeluaran.tambahPengeluaran', [
            'page' => 'Egg Grow'
        ]);
    }

    public function storePengeluaran(Request $request)
    {
        $request->validate([
            'jenis_pengeluaran' => 'required|in:telur pecah,lainnya,beli telur',
            'tanggal' => 'required|date',
            'keterangan' => 'nullable|string',
        ]);

        if ($request->jenis_pengeluaran === 'telur pecah') {
            $request->validate([
                'jenis_telur' => 'required|in:Omega,Biasa',
                'berat_total' => 'required|integer|min:1',
                'nominal_telur' => 'required|integer|min:1',
            ]);
        }

        if ($request->jenis_pengeluaran === 'lainnya') {
            $request->validate([
                'nama_pengeluaran' => 'required|string|max:255',
                'nominal_lainnya' => 'required|integer|min:1',
            ]);
        }

        try {
            DB::transaction(function () use ($request) {

                $saldo = Saldo::where('jenis_saldo', 'toko')->lockForUpdate()->first();

                if (!$saldo) {
                    throw new \Exception('Saldo toko belum tersedia.');
                }

                // ================= TELUR PECAH =================
                if ($request->jenis_pengeluaran === 'telur pecah') {

                    $stokTelur = StokTelur::where('jenis_stok', 'toko')
                        ->where('jenis_telur', $request->jenis_telur)
                        ->lockForUpdate()
                        ->first();

                    if (!$stokTelur) {
                        throw new \Exception('Stok telur ' . $request->jenis_telur . ' tidak tersedia.');
                    }

                    if ($stokTelur->total_stok < $request->berat_total) {
                        throw new \Exception(
                            'Stok telur ' . $request->jenis_telur . ' tidak mencukupi. Sisa: ' .
                                $stokTelur->total_stok . ' gram.'
                        );
                    }

                    if ($saldo->jumlah_saldo < $request->nominal_telur) {
                        throw new \Exception('Saldo toko tidak mencukupi.');
                    }

                    PengeluaranToko::create([
                        'jenis_pengeluaran' => 'telur pecah',
                        'tanggal' => $request->tanggal,
                        'jenis_telur' => $request->jenis_telur,
                        'berat_total' => $request->berat_total,
                        'nominal' => $request->nominal_telur,
                        'keterangan' => $request->keterangan,
                    ]);

                    $stokTelur->decrement('total_stok', $request->berat_total);
                    $saldo->decrement('jumlah_saldo', $request->nominal_telur);
                }

                // ================= LAINNYA =================
                else {

                    if ($saldo->jumlah_saldo < $request->nominal_lainnya) {
                        throw new \Exception('Saldo toko tidak mencukupi.');
                    }

                    PengeluaranToko::create([
                        'jenis_pengeluaran' => 'lainnya',
                        'tanggal' => $request->tanggal,
                        'nama_pengeluaran' => $request->nama_pengeluaran,
                        'nominal' => $request->nominal_lainnya,
                        'keterangan' => $request->keterangan,
                    ]);

                    $saldo->decrement('jumlah_saldo', $request->nominal_lainnya);
                }
            });
        } catch (\Exception $e) {

            return back()
                ->with('error', $e->getMessage())
                ->withInput();
        }

        return redirect()
            ->route('egg-grow.pengeluaran')
            ->with('messageTambahPengeluaran', 'Pengeluaran berhasil disimpan');
    }

    public function editPengeluaran($id)
    {
        $pengeluaran = PengeluaranToko::findOrFail($id);


        return view('egg-grow.pengeluaran.editPengeluaran', [
            'page' => 'Egg Grow',
            'data_pengeluaran' => $pengeluaran,
        ]);
    }

    public function updatePengeluaran(Request $request, $id)
    {
        $pengeluaran = PengeluaranToko::findOrFail($id);

        $request->validate([
            'tanggal' => 'required|date',
            'jenis_pengeluaran' => 'required|in:telur pecah,lainnya,beli telur',
            'keterangan' => 'nullable|string',
        ]);

        try {
            DB::transaction(function () use ($request, $pengeluaran) {

                $saldo = Saldo::where('jenis_saldo', 'toko')
                    ->lockForUpdate()
                    ->firstOrFail();

                // ================= ROLLBACK DATA LAMA =================
                if ($pengeluaran->jenis_pengeluaran === 'telur pecah') {

                    $stokTelurLama = StokTelur::where('jenis_stok', 'toko')
                        ->where('jenis_telur', $pengeluaran->jenis_telur)
                        ->lockForUpdate()
                        ->firstOrFail();

                    // kembalikan stok & saldo
                    $stokTelurLama->increment('total_stok', $pengeluaran->berat_total);
                    $saldo->increment('jumlah_saldo', $pengeluaran->nominal);
                } elseif ($pengeluaran->jenis_pengeluaran === 'lainnya') {
                    $saldo->increment('jumlah_saldo', $pengeluaran->nominal);
                } elseif ($pengeluaran->jenis_pengeluaran === 'beli telur') {
                    // 'beli telur' hanya kembalikan saldo
                    $saldo->increment('jumlah_saldo', $pengeluaran->nominal);
                }

                // ================= UPDATE DATA =================
                if ($pengeluaran->jenis_pengeluaran === 'telur pecah') {

                    $request->validate([
                        'jenis_telur' => 'required|in:Omega,Biasa',
                        'berat_total' => 'required|integer|min:1',
                        'nominal_telur' => 'required|integer|min:1',
                    ]);

                    $stokTelurBaru = StokTelur::where('jenis_stok', 'toko')
                        ->where('jenis_telur', $request->jenis_telur)
                        ->lockForUpdate()
                        ->first();

                    if (!$stokTelurBaru) {
                        throw new \Exception('Stok telur ' . $request->jenis_telur . ' tidak tersedia.');
                    }

                    if ($stokTelurBaru->total_stok < $request->berat_total) {
                        throw new \Exception('Stok telur tidak mencukupi.');
                    }

                    if ($saldo->jumlah_saldo < $request->nominal_telur) {
                        throw new \Exception('Saldo toko tidak mencukupi.');
                    }

                    $pengeluaran->update([
                        'tanggal' => $request->tanggal,
                        'jenis_telur' => $request->jenis_telur,
                        'berat_total' => $request->berat_total,
                        'nominal' => $request->nominal_telur,
                        'keterangan' => $request->keterangan,
                    ]);

                    $stokTelurBaru->decrement('total_stok', $request->berat_total);
                    $saldo->decrement('jumlah_saldo', $request->nominal_telur);
                }

                // ================= LAINNYA =================
                elseif ($pengeluaran->jenis_pengeluaran === 'lainnya') {

                    $request->validate([
                        'nama_pengeluaran' => 'required|string|max:255',
                        'nominal_lainnya' => 'required|integer|min:1',
                    ]);

                    if ($saldo->jumlah_saldo < $request->nominal_lainnya) {
                        throw new \Exception('Saldo toko tidak mencukupi.');
                    }

                    $pengeluaran->update([
                        'tanggal' => $request->tanggal,
                        'nama_pengeluaran' => $request->nama_pengeluaran,
                        'nominal' => $request->nominal_lainnya,
                        'keterangan' => $request->keterangan,
                    ]);

                    $saldo->decrement('jumlah_saldo', $request->nominal_lainnya);
                }

                // ================= BELI TELUR (dari Gudang) =================
                elseif ($pengeluaran->jenis_pengeluaran === 'beli telur') {
                    // 'beli telur' hanya update tanggal dan keterangan, tidak bisa ubah nominal
                    $pengeluaran->update([
                        'tanggal' => $request->tanggal,
                        'keterangan' => $request->keterangan,
                    ]);
                }
            });
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage())->withInput();
        }

        return redirect()
            ->route('egg-grow.pengeluaran')
            ->with('messageUpdatePengeluaran', 'Pengeluaran berhasil diperbarui');
    }

    public function destroyPengeluaran($id)
    {
        try {
            DB::transaction(function () use ($id) {

                $pengeluaran = PengeluaranToko::findOrFail($id);

                $saldo = Saldo::where('jenis_saldo', 'toko')
                    ->lockForUpdate()
                    ->firstOrFail();

                // ================= ROLLBACK =================
                if ($pengeluaran->jenis_pengeluaran === 'telur pecah') {

                    $stokTelur = StokTelur::where('jenis_stok', 'toko')
                        ->where('jenis_telur', $pengeluaran->jenis_telur)
                        ->lockForUpdate()
                        ->firstOrFail();

                    $stokTelur->increment('total_stok', $pengeluaran->berat_total);
                    $saldo->increment('jumlah_saldo', $pengeluaran->nominal);
                } else {
                    $saldo->increment('jumlah_saldo', $pengeluaran->nominal);
                }

                $pengeluaran->delete();
            });
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }

        return redirect()
            ->route('egg-grow.pengeluaran')
            ->with('messageDeletePengeluaran', 'Pengeluaran berhasil dihapus');
    }
}
