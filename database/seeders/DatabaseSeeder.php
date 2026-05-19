<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Incident;
use App\Models\Volunteer;
use App\Models\Equipment;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Data dummy incidents
        Incident::create([
            'location' => 'Pasar Induk',
            'address' => 'Jl. Sudirman No.45, Jakarta Pusat',
            'description' => 'Kebakaran di area los pedagang, api membakar 5 kios',
            'reporter' => 'Budi (Admin)',
            'scale' => 'Sedang',
            'status' => 'Dalam Penanganan',
            'source' => 'Admin',
            'lat' => -6.200000,
            'lng' => 106.816666
        ]);

        Incident::create([
            'location' => 'Perumahan Griya Asri',
            'address' => 'Blok C No.12, Jakarta Selatan',
            'description' => 'Rumah warga terbakar diduga akibat korsleting listrik',
            'reporter' => 'Siti',
            'scale' => 'Besar',
            'status' => 'Laporan Baru',
            'source' => 'Masyarakat',
            'lat' => -6.250000,
            'lng' => 106.800000
        ]);

        // Data dummy volunteers
        Volunteer::create([
            'name' => 'Andi Wijaya',
            'role' => 'Komandan Regu',
            'phone' => '081234567890',
            'blood_type' => 'O',
            'join_date' => '2024-01-15'
        ]);

        Volunteer::create([
            'name' => 'Bambang Susanto',
            'role' => 'Anggota',
            'phone' => '081298765432',
            'blood_type' => 'A',
            'join_date' => '2024-02-20'
        ]);

        Volunteer::create([
            'name' => 'Citra Dewi',
            'role' => 'Tim Medis',
            'phone' => '081355577788',
            'blood_type' => 'AB',
            'join_date' => '2024-03-10'
        ]);

        // Data dummy equipment
        Equipment::create([
            'name' => 'APAR 6kg',
            'category' => 'APAR',
            'quantity' => 5,
            'status' => 'Baik',
            'last_service' => '2025-03-01'
        ]);

        Equipment::create([
            'name' => 'Damkar Mitsubishi',
            'category' => 'Kendaraan',
            'quantity' => 1,
            'status' => 'Baik',
            'last_service' => '2025-02-15'
        ]);

        Equipment::create([
            'name' => 'Helm Pemadam',
            'category' => 'APD',
            'quantity' => 12,
            'status' => 'Perbaikan',
            'last_service' => '2025-01-10'
        ]);

        Equipment::create([
            'name' => 'Gergaji Mesin',
            'category' => 'Alat Pemotong',
            'quantity' => 3,
            'status' => 'Baik',
            'last_service' => '2025-02-28'
        ]);
    }
}