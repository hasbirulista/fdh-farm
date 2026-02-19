<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KandangPakan extends Model
{
    protected $table = 'tb_kandang_pakan';
    protected $fillable = ['kandang_id', 'stok_pakan_id', 'stok'];

    public function stokPakan()
    {
        return $this->belongsTo(StokPakan::class, 'stok_pakan_id');
    }

    public function kandang()
    {
        return $this->belongsTo(Kandang::class, 'kandang_id');
    }
    
}
