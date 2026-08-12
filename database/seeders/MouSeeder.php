<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MouSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Insert sample Mou
        $mouId = \Illuminate\Support\Facades\DB::table('mous')->insertGetId([
            'file_name' => 'Penawaran Layanan IT',
            'intro_text' => 'Bersama surat ini, kami menawarkan jasa pemasangan infrastruktur jaringan.',
            'terms' => 'Pembayaran 50% di muka, 50% setelah selesai.',
            'lokasi' => 'Tegal',
            'tanggal' => date('Y-m-d'),
            'customer' => 'PT. Inovasi Teknologi',
            'grand_total' => 1500000.00,
            'kry_kode' => 'KRY001',
            'created_at' => now(),
        ]);

        // Insert sample Mou Items
        \Illuminate\Support\Facades\DB::table('mou_items')->insert([
            [
                'mou_id' => $mouId,
                'item_no' => 1,
                'spesifikasi' => 'Instalasi Jaringan LAN (10 Titik)',
                'qty' => 1,
                'harga' => 1000000.00,
                'total' => 1000000.00,
            ],
            [
                'mou_id' => $mouId,
                'item_no' => 2,
                'spesifikasi' => 'Konfigurasi Mikrotik Router',
                'qty' => 1,
                'harga' => 500000.00,
                'total' => 500000.00,
            ]
        ]);
    }
}
