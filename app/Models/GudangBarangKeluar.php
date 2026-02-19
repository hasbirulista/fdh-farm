<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GudangBarangKeluar extends Model
{
    protected $table = 'tb_gudang_barang_keluar';
    // inisialisasi primaryKey dalam tabel
    protected $primaryKey = 'id';
    protected $guarded = ['id'];

    public function eggGrowBarangMasuk()
    {
        return $this->hasOne(EggGrowBarangMasuk::class, 'gudang_barang_keluar_id');
    }
}
