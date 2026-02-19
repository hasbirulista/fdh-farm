<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StokPakan extends Model
{
    protected $table = 'tb_stok_pakan';
    // inisialisasi primaryKey dalam tabel
    protected $fillable = [
        'tanggal',
        'jenis_pakan',
        'berat_total',
    ];

    public function kandang()
    {
        return $this->hasMany(KandangPakan::class);
    }
}
