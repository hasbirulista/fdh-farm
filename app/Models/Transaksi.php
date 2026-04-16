<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    protected $table = 'tb_transaksi';

    protected $fillable = [
        'pelanggan_id',
        'tanggal_transaksi',
        'tanggal_pelunasan',
        'status_pelunasan',
        'jenis_telur',
        'total_berat',
        'harga_beli_kilo',
        'harga_jual_kilo',
        'total_harga',
        'pembayaran',
    ];


    /**
     * Relasi ke pelanggan
     */
    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class, 'pelanggan_id');
    }

    // Untuk transaksi terakhir
    public function transaksiTerakhir()
    {
        return $this->hasOne(Transaksi::class, 'pelanggan_id')->latest('tanggal_transaksi');
    }
}
