<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // ── Owner (Satu-satunya akun yang di-seed di awal inisialisasi sistem) ──
        User::firstOrCreate(
            ['email' => 'owner@gmail.com'],
            [
                'name'      => 'Owner GMF',
                'email'     => 'owner@gmail.com',
                'password'  => Hash::make('password'),
                'role'      => 'owner',
                'phone'     => '081234567890',
                'is_active' => true,
            ]
        );
    }
}
