<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // ── Owner ─────────────────────────────────────────────────────────────
        User::firstOrCreate(
            ['email' => 'owner@gmail.com'],
            [
                'name'     => 'Owner GMF',
                'email'    => 'owner@gmail.com',
                'password' => Hash::make('password'),
                'role'     => 'owner',
                'phone'    => '081234567890',
                'is_active' => true,
            ]
        );

        // ── Admin ─────────────────────────────────────────────────────────────
        User::firstOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name'     => 'Admin GMF',
                'email'    => 'admin@gmail.com',
                'password' => Hash::make('password'),
                'role'     => 'admin',
                'phone'    => '081234567891',
                'is_active' => true,
            ]
        );

        // ── Coach 1 ───────────────────────────────────────────────────────────
        $coach1User = User::firstOrCreate(
            ['email' => 'coach1@gmail.com'],
            [
                'name'     => 'Rizky Pratama',
                'email'    => 'coach1@gmail.com',
                'password' => Hash::make('password'),
                'role'     => 'coach',
                'phone'    => '081234567892',
                'is_active' => true,
            ]
        );

        \App\Models\Coach::firstOrCreate(
            ['user_id' => $coach1User->id],
            [
                'full_name'        => 'Rizky Pratama',
                'phone'            => '081234567892',
                'bio'              => 'Instruktur Zumba berpengalaman 5 tahun. Bersertifikat Zumba International.',
                'rate_per_session' => 100000,
                'is_active'        => true,
            ]
        );

        // ── Coach 2 ───────────────────────────────────────────────────────────
        $coach2User = User::firstOrCreate(
            ['email' => 'coach2@gmail.com'],
            [
                'name'     => 'Sari Dewi',
                'email'    => 'coach2@gmail.com',
                'password' => Hash::make('password'),
                'role'     => 'coach',
                'phone'    => '081234567893',
                'is_active' => true,
            ]
        );

        \App\Models\Coach::firstOrCreate(
            ['user_id' => $coach2User->id],
            [
                'full_name'        => 'Sari Dewi',
                'phone'            => '081234567893',
                'bio'              => 'Instruktur Pound Fit dan Yoga. Lulusan Sport Science Universitas Negeri Jakarta.',
                'rate_per_session' => 120000,
                'is_active'        => true,
            ]
        );
    }
}
