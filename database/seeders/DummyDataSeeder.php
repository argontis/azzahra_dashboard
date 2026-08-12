<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DummyDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = \Faker\Factory::create('id_ID');

        // 1. Data Master - Produk
        for ($i = 1; $i <= 5; $i++) {
            \Illuminate\Support\Facades\DB::table('produk')->insert([
                'kode_barang' => 'PRD' . str_pad($i, 3, '0', STR_PAD_LEFT),
                'nama_produk' => 'Produk ' . $faker->word,
                'deskripsi' => $faker->sentence,
                'harga' => $faker->randomFloat(2, 50000, 500000),
                'created_at' => now(),
            ]);
        }

        // 2. Data Master - Costomer
        for ($i = 1; $i <= 5; $i++) {
            \Illuminate\Support\Facades\DB::table('costomer')->insert([
                'cos_nama' => $faker->name,
                'cos_alamat' => $faker->address,
                'cos_hp' => $faker->phoneNumber,
                'cos_tipe' => $faker->randomElement(['Laptop', 'PC', 'Printer']),
                'cos_model' => $faker->word,
                'cos_no_seri' => $faker->ean13,
                'cos_asesoris' => 'Charger',
                'cos_status' => 'Baru',
                'cos_keluhan' => 'Mati total',
                'created_at' => now(),
            ]);
        }

        // 3. Data Master - Vouchers
        for ($i = 1; $i <= 5; $i++) {
            \Illuminate\Support\Facades\DB::table('vouchers')->insert([
                'voucher_code' => strtoupper($faker->bothify('VOUCHER-####')),
                'description' => 'Diskon ' . $i,
                'discount_percent' => $faker->randomFloat(2, 5, 20),
                'start_date' => now(),
                'end_date' => now()->addDays(30),
                'max_usage' => 10,
                'status' => 'active',
                'created_at' => now(),
            ]);
        }

        // Get FK references
        $customers = \Illuminate\Support\Facades\DB::table('costomer')->pluck('id_costomer')->toArray();
        $karyawans = \Illuminate\Support\Facades\DB::table('karyawan')->pluck('kry_kode')->toArray();

        if (empty($customers) || empty($karyawans)) return;

        // 4. Transaksi & Operasional
        for ($i = 1; $i <= 5; $i++) {
            $cos_kode = $faker->randomElement($customers);
            $kry_kode = $faker->randomElement($karyawans);
            $trans_kode = \Illuminate\Support\Facades\DB::table('transaksi')->insertGetId([
                'cos_kode' => $cos_kode,
                'kry_kode' => $kry_kode,
                'trans_status' => $faker->randomElement(['Baru', 'Diproses', 'Selesai']),
                'cos_tanggal' => now()->format('Y-m-d'),
                'cos_jam' => now()->format('H:i:s'),
                'trans_discount' => 0,
                'trans_total' => $faker->randomFloat(2, 100000, 1000000),
                'created_at' => now(),
            ]);

            \Illuminate\Support\Facades\DB::table('transaksi_detail')->insert([
                'trans_kode' => $trans_kode,
                'kry_kode' => $kry_kode,
                'dtl_jml_bayar' => 50000,
                'dtl_jenis_bayar' => 'DP',
                'dtl_bank' => 'BCA',
                'dtl_tanggal' => now()->format('Y-m-d'),
                'dtl_jam' => now()->format('H:i:s'),
                'created_at' => now(),
            ]);

            \Illuminate\Support\Facades\DB::table('tindakan')->insert([
                'trans_kode' => $trans_kode,
                'tdkn_barang' => 'Pengecekan dan Service',
                'tdkn_harga' => 50000,
                'tdkn_qty' => 1,
                'tdkn_subtot' => 50000,
                'created_at' => now(),
            ]);

            \Illuminate\Support\Facades\DB::table('vocer')->insert([
                'trans_kode' => $trans_kode,
                'voc_jumlah' => 10000,
                'voc_tanggal' => now()->format('Y-m-d'),
                'voc_status' => 'Used',
                'created_at' => now(),
            ]);

            \Illuminate\Support\Facades\DB::table('transaksi_return')->insert([
                'trans_kode' => $trans_kode,
                'ret_jml' => 10000,
                'ret_tanggal' => now()->format('Y-m-d'),
                'created_at' => now(),
            ]);

            \Illuminate\Support\Facades\DB::table('order_list')->insert([
                'trans_kode' => $trans_kode,
                'cos_kode' => $cos_kode,
                'kry_kode' => $kry_kode,
                'trans_total' => 200000,
                'trans_status' => 'repairing',
                'merek' => 'Lenovo',
                'device' => 'Laptop',
                'created_at' => now(),
            ]);

            \Illuminate\Support\Facades\DB::table('ketersediaan_sparepart')->insert([
                'trans_kode' => $trans_kode,
                'cos_nama' => 'Budi',
                'barang_nama' => 'RAM 8GB',
                'status' => 'menunggu',
                'ketersediaan' => 'tidak_ada',
                'created_at' => now(),
            ]);
            
            \Illuminate\Support\Facades\DB::table('order_part_markings')->insert([
                'trans_kode' => $trans_kode,
                'is_ordered' => 'yes',
                'created_at' => now(),
            ]);

            \Illuminate\Support\Facades\DB::table('order_part_approvals')->insert([
                'trans_kode' => $trans_kode,
                'type' => 'oow',
                'approval_status' => 'pending',
                'created_at' => now(),
            ]);
        }

        // 5. Data HR & Administrasi
        for ($i = 1; $i <= 5; $i++) {
            $kry_kode = $faker->randomElement($karyawans);

            \Illuminate\Support\Facades\DB::table('absensi')->insert([
                'tanggal' => now()->format('Y-m-d'),
                'id_karyawan' => $kry_kode,
                'nama_karyawan' => 'Karyawan ' . $i,
                'status' => 'Hadir',
                'jam_masuk' => '08:00:00',
                'jam_pulang' => '17:00:00',
                'created_at' => now(),
            ]);

            \Illuminate\Support\Facades\DB::table('absensi_wfh')->insert([
                'tanggal' => now()->format('Y-m-d'),
                'id_karyawan' => $kry_kode,
                'nama_karyawan' => 'Karyawan WFH ' . $i,
                'status' => 'Hadir',
                'jam_masuk' => '08:00:00',
                'jam_pulang' => '17:00:00',
                'created_at' => now(),
            ]);

            \Illuminate\Support\Facades\DB::table('kpi')->insert([
                'id_karyawan' => $kry_kode,
                'nama_karyawan' => 'Karyawan ' . $i,
                'kedisiplinan' => 80,
                'kualitas_kerja' => 85,
                'produktivitas' => 90,
                'kerja_tim' => 85,
                'total' => 340,
                'rata_rata' => 85,
                'kategori' => 'Baik',
                'created_at' => now(),
            ]);

            \Illuminate\Support\Facades\DB::table('arsip')->insert([
                'tipe' => 'Invoice',
                'nama' => 'Arsip ' . $i,
                'tanggal' => now()->format('Y-m-d'),
                'no_hp' => $faker->phoneNumber,
                'alamat' => $faker->address,
                'created_at' => now(),
            ]);

            \Illuminate\Support\Facades\DB::table('laporan_mingguan')->insert([
                'id_karyawan' => $kry_kode,
                'nama_karyawan' => 'Karyawan ' . $i,
                'periode' => 'Minggu 1',
                'target_mingguan' => 'Service 10 Unit',
                'tugas_dilakukan' => 'Service 8 Unit',
                'hasil' => '80% tercapai',
                'created_at' => now(),
            ]);

            \Illuminate\Support\Facades\DB::table('pencatatan')->insert([
                'nama_barang' => 'Sparepart ' . $i,
                'qty' => 5,
                'harga_satuan' => 10000,
                'total' => 50000,
                'tanggal' => now()->format('Y-m-d'),
                'created_at' => now(),
            ]);
        }
    }
}
