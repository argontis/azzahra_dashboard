<?php

namespace Database\Seeders;

use App\Models\Karyawan;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class KaryawanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            ['username' => 'test_admin', 'level' => 'Admin', 'nama' => 'Test Admin'],
            ['username' => 'test_pimpinan', 'level' => 'Pimpinan', 'nama' => 'Test Pimpinan'],
            ['username' => 'test_hr', 'level' => 'HR', 'nama' => 'Test HR'],
            ['username' => 'test_teknisi', 'level' => 'Teknisi', 'nama' => 'Test Teknisi'],
            ['username' => 'test_kasir', 'level' => 'Kasir', 'nama' => 'Test Kasir'],
            ['username' => 'test_cs', 'level' => 'Customer Service', 'nama' => 'Test CS'],
            ['username' => 'test_magang', 'level' => 'Magang / PKL', 'nama' => 'Test Magang/PKL'],
        ];

        foreach ($roles as $role) {
            Karyawan::updateOrCreate(
                ['kry_username' => $role['username']],
                [
                    'kry_pswd' => Hash::make('password123'),
                    'kry_nama' => $role['nama'],
                    'kry_level' => $role['level'],
                    'kry_telp' => '081234567890',
                    'kry_join_date' => date('Y-m-d'),
                    'kry_status' => 1,
                ]
            );
        }
    }
}
