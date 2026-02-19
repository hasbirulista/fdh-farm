<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GudangBarangMasuk extends Model
{
    protected $table = 'tb_gudang_barang_masuk';
    // inisialisasi primaryKey dalam tabel
    protected $primaryKey = 'id';
    protected $guarded = ['id'];

    public function produksi()
    {
        return $this->belongsTo(Produksi::class, 'produksi_id','id');
    }
}
