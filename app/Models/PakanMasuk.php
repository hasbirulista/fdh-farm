<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PakanMasuk extends Model
{
    protected $table = 'tb_pakan_masuk';
    // inisialisasi primaryKey dalam tabel
    protected $primaryKey = 'id';
    protected $guarded = ['id'];
}
