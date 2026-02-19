<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Produksi extends Model
{
    protected $table = 'tb_produksi';
    // inisialisasi primaryKey dalam tabel
    protected $primaryKey = 'id';
    protected $guarded = ['id'];

    public function gudangMasuk()
    {
        return $this->hasOne(GudangBarangMasuk::class, 'produksi_id', 'id');
    }

    public function kandang()
    {
        return $this->belongsTo(Kandang::class, 'kandang_id');
    }
}
