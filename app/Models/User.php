<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * Mass assignable
     */
    protected $fillable = [
        'username',
        'nama',
        'name', 
        'no_hp',
        'email',
        'password',
        'role',
        'kandang_id', // ← wajib
    ];

    /**
     * Hidden fields
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Casts
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /* ===================== RELATION ===================== */

    public function kandang()
{
    return $this->belongsTo(Kandang::class, 'kandang_id');
}

    /* ===================== ROLE HELPER ===================== */

    public function isOwner()
    {
        return $this->role === 'owner';
    }

    public function isAnakKandang()
    {
        return $this->role === 'anak_kandang';
    }

    public function isKepalaKandang()
    {
        return $this->role === 'kepala_kandang';
    }

    public function isKepalaGudang()
    {
        return $this->role === 'kepala_gudang';
    }

    public function isAdminToko()
    {
        return $this->role === 'admin_toko';
    }
}
