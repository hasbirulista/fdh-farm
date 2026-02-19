<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kandang extends Model
{
    protected $table = 'tb_kandang';
    protected $primaryKey = 'id';
    protected $guarded = ['id'];

    public function pakan()
    {
        return $this->hasMany(KandangPakan::class);
    }

    public function kandangPakan()
    {
        return $this->hasMany(KandangPakan::class, 'kandang_id');
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
