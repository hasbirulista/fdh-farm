<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DistribusiPakan extends Model
{
    //
    protected $table = 'tb_distribusi_pakan';
    // inisialisasi primaryKey dalam tabel
    protected $primaryKey = 'id';
    protected $guarded = ['id'];

    public function kandang()
    {
        return $this->belongsTo(Kandang::class, 'kandang_id');
    }

    public function stokPakan()
    {
        return $this->belongsTo(StokPakan::class, 'stok_pakan_id');
    }
}
