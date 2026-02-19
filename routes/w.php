<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\{
    DashboardOwnerController,
    EggGrowController,
    GudangController,
    PakanController,
    KandangController,
    RepeatOrderController,
    ProfileController,
    UserController
};

/*
|--------------------------------------------------------------------------
| ROOT
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return Auth::check()
        ? redirect()->route('dashboard')
        : redirect()->route('login');
});

/*
|--------------------------------------------------------------------------
| DASHBOARD (WAJIB LOGIN)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->prefix('dashboard')->group(function () {

    // ===== DASHBOARD OWNER =====
    Route::get('/', [DashboardOwnerController::class, 'index'])
        ->name('dashboard');

    Route::post('/saldo-gudang', [DashboardOwnerController::class, 'updateSaldoGudang'])
        ->name('dashboard.saldo.gudang');

    Route::post('/saldo-toko', [DashboardOwnerController::class, 'updateSaldoToko'])
        ->name('dashboard.saldo.toko');

    /*
    |--------------------------------------------------------------------------
    | KANDANG
    |--------------------------------------------------------------------------
    */
    Route::prefix('kandang')->group(function () {

        Route::get('/', [KandangController::class, 'kandang'])->name('kandang.index');

        Route::get('/tambah-kandang', [KandangController::class, 'tambahKandang'])->name('kandang.tambah');
        Route::post('/tambah-kandang', [KandangController::class, 'storeKandang'])->name('kandang.store');
        Route::put('/tambah-kandang/{id}', [KandangController::class, 'updateKandang'])->name('kandang.update');
        Route::delete('/tambah-kandang/{id}', [KandangController::class, 'destroyKandang'])->name('kandang.destroy');

        Route::get('/produksi', [KandangController::class, 'produksiPerKandang'])->name('produksi.all');
        Route::get('/produksi/kandang/{namaKandang}', [KandangController::class, 'produksiPerKandang'])->name('produksi.perKandang');

        Route::get('/produksi/tambah', [KandangController::class, 'tambahProduksi'])->name('produksi.tambah');
        Route::post('/produksi', [KandangController::class, 'storeProduksi'])->name('produksi.store');
        Route::get('/produksi/{id}/edit', [KandangController::class, 'editProduksi'])->name('produksi.edit');
        Route::put('/produksi/{id}', [KandangController::class, 'updateProduksi'])->name('produksi.update');
        Route::delete('/produksi/{id}', [KandangController::class, 'destroyProduksi'])->name('produksi.destroy');
    });

    /*
    |--------------------------------------------------------------------------
    | GUDANG
    |--------------------------------------------------------------------------
    */
    Route::prefix('gudang')->group(function () {

        Route::get('/', [GudangController::class, 'gudang'])->name('gudang.dashboard');

        // BARANG MASUK
        Route::get('/barang-masuk', [GudangController::class, 'barangMasuk'])->name('gudang.barangMasuk');
        Route::get('/barang-masuk/tambah', [GudangController::class, 'tambahBarangMasuk'])->name('gudang.tambahBarangMasuk');
        Route::post('/barang-masuk/store', [GudangController::class, 'storeBarangMasuk'])->name('gudang.storeBarangMasuk');
        Route::get('/barang-masuk/{id}/edit', [GudangController::class, 'editBarangMasuk'])->name('gudang.editBarangMasuk');
        Route::put('/barang-masuk/{id}', [GudangController::class, 'updateBarangMasuk'])->name('gudang.updateBarangMasuk');
        Route::delete('/barang-masuk/{id}', [GudangController::class, 'destroyBarangMasuk'])->name('gudang.destroyBarangMasuk');

        // BARANG KELUAR
        Route::get('/barang-keluar', [GudangController::class, 'barangKeluar'])->name('gudang.barangKeluar');
        Route::get('/barang-keluar/tambah', [GudangController::class, 'tambahBarangKeluar'])->name('gudang.tambahBarangKeluar');
        Route::post('/barang-keluar', [GudangController::class, 'storeBarangKeluar'])->name('gudang.storeBarangKeluar');
        Route::get('/barang-keluar/{id}/edit', [GudangController::class, 'editBarangKeluar'])->name('gudang.editBarangKeluar');
        Route::put('/barang-keluar/update/{id}', [GudangController::class, 'updateBarangKeluar'])->name('gudang.updateBarangKeluar');
        Route::delete('/barang-keluar/{id}', [GudangController::class, 'destroyBarangKeluar'])->name('gudang.destroyBarangKeluar');

        // PENGELUARAN
        Route::get('/pengeluaran', [GudangController::class, 'pengeluaran'])->name('gudang.pengeluaran');
        Route::get('/pengeluaran/tambah', [GudangController::class, 'tambahPengeluaran'])->name('gudang.tambahPengeluaran');
        Route::post('/pengeluaran', [GudangController::class, 'storePengeluaran'])->name('gudang.storePengeluaran');
        Route::get('/pengeluaran/{id}/edit', [GudangController::class, 'editPengeluaran'])->name('gudang.editPengeluaran');
        Route::put('/pengeluaran/update/{id}', [GudangController::class, 'updatePengeluaran'])->name('gudang.updatePengeluaran');
        Route::delete('/pengeluaran/{id}', [GudangController::class, 'destroyPengeluaran'])->name('gudang.destroyPengeluaran');
    });

    /*
    |--------------------------------------------------------------------------
    | PAKAN
    |--------------------------------------------------------------------------
    */
    Route::prefix('pakan')->name('pakan.')->group(function () {
        // ======================
        // PAKAN UTAMA
        // ======================
        Route::get('/', [PakanController::class, 'pakan'])->name('index');

        // Distribusi pakan per kandang
        Route::get('/kandang/{kandangId}', [PakanController::class, 'byKandang'])->name('byKandang');

        // ======================
        // PAKAN MASUK
        // ======================
        Route::get('/pakan-masuk', [PakanController::class, 'pakanMasuk'])->name('masuk.index');
        Route::get('/pakan-masuk/tambah', [PakanController::class, 'tambahPakanMasuk'])->name('masuk.create');
        Route::post('/pakan-masuk', [PakanController::class, 'storePakanMasuk'])->name('masuk.store');
        Route::get('/pakan-masuk/{id}/edit', [PakanController::class, 'editPakanMasuk'])->name('masuk.edit');
        Route::put('/pakan-masuk/{id}', [PakanController::class, 'updatePakanMasuk'])->name('masuk.update');
        Route::delete('/pakan-masuk/{id}', [PakanController::class, 'destroyPakanMasuk'])->name('masuk.destroy');

        // ======================
        // DISTRIBUSI PAKAN
        // ======================
        Route::get('/distribusi', [PakanController::class, 'distribusiPakan'])->name('distribusi.index');
        Route::get('/distribusi/tambah', [PakanController::class, 'tambahDistribusiPakan'])->name('distribusi.create');
        Route::post('/distribusi', [PakanController::class, 'storeDistribusiPakan'])->name('distribusi.store');
        Route::get('/distribusi/{id}/edit', [PakanController::class, 'editDistribusiPakan'])->name('distribusi.edit');
        Route::put('/distribusi/{id}', [PakanController::class, 'updateDistribusiPakan'])->name('distribusi.update');
        Route::delete('/distribusi/{id}', [PakanController::class, 'destroyDistribusiPakan'])->name('distribusi.destroy');
    });

    /*
    |--------------------------------------------------------------------------
    | EGG GROW
    |--------------------------------------------------------------------------
    */
    Route::prefix('egg-grow')->name('egg-grow.')->group(function () {
        // ======================
        // DASHBOARD
        // ======================
        Route::get('/', [EggGrowController::class, 'dashboard'])->name('dashboard');

        // ======================
        // BARANG MASUK
        // ======================
        Route::get('/barang-masuk', [EggGrowController::class, 'barangMasuk'])->name('barang-masuk.index');

        // ======================
        // PELANGGAN
        // ======================
        Route::get('/pelanggan', [EggGrowController::class, 'pelanggan'])->name('pelanggan.index');
        Route::get('/pelanggan/tambah', [EggGrowController::class, 'tambahPelanggan'])->name('pelanggan.create');
        Route::post('/pelanggan', [EggGrowController::class, 'storePelanggan'])->name('pelanggan.store');
        Route::get('/pelanggan/{id}/edit', [EggGrowController::class, 'editPelanggan'])->name('pelanggan.edit');
        Route::put('/pelanggan/{id}', [EggGrowController::class, 'updatePelanggan'])->name('pelanggan.update');
        Route::delete('/pelanggan/{id}', [EggGrowController::class, 'destroyPelanggan'])->name('pelanggan.destroy');

        // ======================
        // TRANSAKSI PENJUALAN
        // ======================
        Route::get('/transaksi', [EggGrowController::class, 'transaksi'])->name('transaksi.index');
        Route::get('/transaksi/tambah', [EggGrowController::class, 'tambahTransaksi'])->name('transaksi.create');
        Route::post('/transaksi', [EggGrowController::class, 'storeTransaksi'])->name('transaksi.store');
        Route::get('/transaksi/{id}/edit', [EggGrowController::class, 'editTransaksi'])->name('transaksi.edit');
        Route::put('/transaksi/{id}', [EggGrowController::class, 'updateTransaksi'])->name('transaksi.update');
        Route::delete('/transaksi/{id}', [EggGrowController::class, 'destroyTransaksi'])->name('transaksi.destroy');

        // PENGELUARAN
        Route::get('/pengeluaran', [EggGrowController::class, 'pengeluaran'])->name('pengeluaran');
        Route::get('/pengeluaran/tambah', [EggGrowController::class, 'tambahPengeluaran'])->name('tambahPengeluaran');
        Route::post('/pengeluaran', [EggGrowController::class, 'storePengeluaran'])->name('storePengeluaran');
        Route::get('/pengeluaran/{id}/edit', [EggGrowController::class, 'editPengeluaran'])->name('editPengeluaran');
        Route::put('/pengeluaran/update/{id}', [EggGrowController::class, 'updatePengeluaran'])->name('updatePengeluaran');
        Route::delete('/pengeluaran/{id}', [EggGrowController::class, 'destroyPengeluaran'])->name('destroyPengeluaran');


        Route::get('/pelanggan-ranking', [EggGrowController::class, 'rankingPelanggan'])->name('pelanggan-ranking');
        // ======================
        // FOLLOW UP REPEAT ORDER
        // ======================
        Route::get('/follow-up', [RepeatOrderController::class, 'followUp'])->name('follow-up');
    });

    /*
    |--------------------------------------------------------------------------
    | PROFILE
    |--------------------------------------------------------------------------
    */
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
});

/*
|--------------------------------------------------------------------------
| OWNER ONLY
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:owner'])
    ->prefix('dashboard')
    ->group(function () {

        Route::get('/', [DashboardOwnerController::class, 'index'])
            ->name('dashboard');

        Route::resource('users', UserController::class)
            ->except(['show']);
    });

Route::middleware(['auth', 'role:owner,kepala_gudang'])
    ->prefix('dashboard/gudang')
    ->group(function () {

        Route::get('/', [GudangController::class, 'gudang']);
        Route::get('/barang-masuk', [GudangController::class, 'barangMasuk']);
        Route::get('/barang-keluar', [GudangController::class, 'barangKeluar']);
        Route::get('/pengeluaran', [GudangController::class, 'pengeluaran']);
    });

Route::middleware(['auth', 'role:owner,anak_kandang,kepala_kandang'])
    ->prefix('dashboard/kandang')
    ->group(function () {

        
        
        Route::get('/produksi', [KandangController::class, 'produksiPerKandang']);
    });

Route::middleware(['auth', 'role:owner,kepala_kandang'])
    ->prefix('dashboard/pakan')
    ->group(function () {
        Route::get('/', [KandangController::class, 'kandang']);
        Route::get('/distribusi', [PakanController::class, 'distribusiPakan']);
    });

Route::middleware(['auth', 'role:owner,admin_toko'])
    ->prefix('dashboard/egg-grow')
    ->group(function () {

        Route::get('/', [EggGrowController::class, 'dashboard']);
        Route::get('/barang-masuk', [EggGrowController::class, 'barangMasuk']);
        Route::get('/pelanggan', [EggGrowController::class, 'pelanggan']);
        Route::get('/transaksi', [EggGrowController::class, 'transaksi']);
        Route::get('/pengeluaran', [EggGrowController::class, 'pengeluaran']);
    });

/*
|--------------------------------------------------------------------------
| LOGOUT (BREEZE STYLE)
|--------------------------------------------------------------------------
*/
Route::post('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/login');
})->middleware('auth')->name('logout');

require __DIR__ . '/auth.php';
