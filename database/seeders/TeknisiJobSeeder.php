<?php

namespace Database\Seeders;

use Faker\Factory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TeknisiJobSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Factory::create('id_ID');

        $teknisis = DB::table('karyawan')->where('kry_level', 'Teknisi')->pluck('kry_kode')->toArray();

        if (empty($teknisis)) {
            return;
        }

        $scenarios = [
            [
                'trans_status' => 'Baru',
                'order_status' => 'menunggu',
                'has_tindakan' => false,
            ],
            [
                'trans_status' => 'Diproses',
                'order_status' => 'repairing',
                'has_tindakan' => true,
            ],
            [
                'trans_status' => 'Lunas',
                'order_status' => 'selesai',
                'has_tindakan' => true,
            ],
        ];

        // Untuk setiap teknisi, buat 3 transaksi dengan status berbeda
        foreach ($teknisis as $kry_kode) {
            foreach ($scenarios as $scenario) {
                // Buat customer baru untuk setiap transaksi
                $cos_kode = 'COS-'.strtoupper(substr(md5(uniqid()), 0, 6));

                DB::table('costomer')->insert([
                    'id_costomer' => $cos_kode,
                    'cos_nama' => $faker->name,
                    'cos_alamat' => $faker->address,
                    'cos_hp' => $faker->phoneNumber,
                    'cos_tipe' => $faker->randomElement(['Laptop', 'PC', 'Printer']),
                    'cos_model' => $faker->randomElement(['Lenovo', 'Asus', 'HP', 'Acer', 'Dell']),
                    'cos_no_seri' => $faker->ean13,
                    'cos_asesoris' => 'Charger',
                    'cos_status' => 'Baru',
                    'cos_keluhan' => 'Layar mati',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                $trans_kode = 'TRX-'.strtoupper(substr(md5(uniqid()), 0, 6));

                DB::table('transaksi')->insert([
                    'trans_kode' => $trans_kode,
                    'cos_kode' => $cos_kode,
                    'kry_kode' => $kry_kode,
                    'trans_status' => $scenario['trans_status'],
                    'cos_tanggal' => now()->format('Y-m-d'),
                    'cos_jam' => now()->format('H:i:s'),
                    'trans_discount' => 0,
                    'trans_total' => $faker->randomFloat(2, 100000, 1000000),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                DB::table('order_list')->insert([
                    'trans_kode' => $trans_kode,
                    'cos_kode' => $cos_kode,
                    'kry_kode' => $kry_kode,
                    'trans_total' => 200000,
                    'trans_status' => $scenario['order_status'],
                    'merek' => $faker->randomElement(['Lenovo', 'Asus', 'HP', 'Acer', 'Dell']),
                    'device' => 'Laptop',
                    'created_at' => now(),
                ]);

                if ($scenario['has_tindakan']) {
                    DB::table('tindakan')->insert([
                        'trans_kode' => $trans_kode,
                        'tdkn_barang' => 'Pengecekan dan Service '.$scenario['trans_status'],
                        'tdkn_harga' => 50000,
                        'tdkn_qty' => 1,
                        'tdkn_subtot' => 50000,
                        'created_at' => now(),
                    ]);
                }
            }
        }
    }
}
