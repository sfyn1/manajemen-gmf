<?php

namespace Database\Seeders;

use App\Models\MembershipPackage;
use Illuminate\Database\Seeder;

class MembershipPackageSeeder extends Seeder
{
    public function run(): void
    {
        MembershipPackage::firstOrCreate(
            ['name' => 'Member Reguler (1 Bulan)'],
            [
                'type'              => 'monthly_regular',
                'price'             => 150000,
                'duration_days'     => 30,
                'description'       => 'Akses gym penuh selama 30 hari + bebas mengikuti kelas grup.',
                'required_document' => 'ktp',
                'is_active'         => true,
            ]
        );

        MembershipPackage::firstOrCreate(
            ['name' => 'Member Pelajar (1 Bulan)'],
            [
                'type'              => 'monthly_student',
                'price'             => 100000,
                'duration_days'     => 30,
                'description'       => 'Khusus siswa/mahasiswa aktif (wajib melampirkan Kartu Pelajar/KTM).',
                'required_document' => 'ktm',
                'is_active'         => true,
            ]
        );

        MembershipPackage::firstOrCreate(
            ['name' => 'Daily Pass (1 Hari)'],
            [
                'type'              => 'daily',
                'price'             => 25000,
                'duration_days'     => 1,
                'description'       => 'Akses gym 1x kunjungan untuk 1 hari.',
                'required_document' => 'ktp',
                'is_active'         => true,
            ]
        );
    }
}
