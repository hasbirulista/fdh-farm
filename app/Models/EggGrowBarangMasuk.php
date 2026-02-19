<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EggGrowBarangMasuk extends Model
{
    protected $table = 'tb_egg_grow_barang_masuk';
    // inisialisasi primaryKey dalam tabel
    protected $primaryKey = 'id';
    protected $guarded = ['id'];

    public function gudangBarangKeluar()
    {
        return $this->belongsTo(GudangBarangKeluar::class, 'gudang_barang_keluar_id');
    }
}
