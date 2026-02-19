<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pelanggan extends Model
{
    protected $table = 'tb_pelanggan';

    protected $fillable = [
        'nama_pelanggan',
        'no_hp',
        'alamat',
        'repeat_order_aktif',
        'repeat_order_hari'
    ];

    /**
     * Semua transaksi pelanggan
     */
    public function transaksi()
    {
        return $this->hasMany(Transaksi::class, 'pelanggan_id');
    }

    public function transaksiTerakhir()
    {
        return $this->hasOne(Transaksi::class, 'pelanggan_id')
            ->latestOfMany('tanggal_transaksi');
    }
}
