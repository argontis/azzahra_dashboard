<?php

namespace Database\Seeders;

use App\Models\Karyawan;
use Faker\Factory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class TeknisiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Factory::create('id_ID');

        // Tambahkan 15 data dummy Teknisi
        for ($i = 1; $i <= 15; $i++) {
            Karyawan::create([
                'kry_username' => 'teknisi_' . $i . '_' . $faker->userName,
                'kry_pswd' => Hash::make('password123'),
                'kry_nama' => 'Teknisi ' . $faker->firstName,
                'kry_level' => 'Teknisi',
                'kry_telp' => $faker->phoneNumber,
                'kry_alamat' => $faker->address,
                'kry_join_date' => $faker->dateTimeBetween('-2 years', 'now')->format('Y-m-d'),
                'kry_status' => 1,
            ]);
        }
    }
}
