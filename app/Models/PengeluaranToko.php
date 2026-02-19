<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PengeluaranToko extends Model
{
    protected $table = 'tb_pengeluaran_toko';
    protected $fillable = [
        'tanggal',
        'jenis_pengeluaran',
        'jenis_telur',
        'berat_total',
        'nama_pengeluaran',
        'nominal',
        'keterangan',
    ];
}
