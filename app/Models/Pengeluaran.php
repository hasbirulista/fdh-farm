<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pengeluaran extends Model
{
    protected $table = 'tb_pengeluaran';
    protected $fillable = [
        'tanggal',
        'jenis_pengeluaran',
        'jenis_pakan',
        'berat_total',
        'harga_kilo',
        'total_harga',
        'nama_pengeluaran',
        'keterangan',
    ];
}
