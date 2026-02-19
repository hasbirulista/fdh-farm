<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class OwnerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'username' => 'owner',
            'nama' => 'Owner Utama',
            'name' => 'Owner Utama',
            'no_hp' => '081234567890',
            'email' => 'owner@example.com',
            'password' => Hash::make('123456'), // nanti bisa diubah
            'role' => 'owner',
        ]);
    }
}
