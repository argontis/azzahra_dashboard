<?php

require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Kernel::class);
$kernel->bootstrap();

use App\Models\Karyawan;
use App\Models\LaporanMingguan;
use Illuminate\Contracts\Console\Kernel;

$karyawan = Karyawan::first();
if ($karyawan) {
    LaporanMingguan::updateOrCreate(
        [
            'id_karyawan' => $karyawan->kry_kode,
            'periode' => date('Y').'-W'.date('W'),
        ],
        [
            'nama_karyawan' => $karyawan->kry_nama,
            'posisi' => $karyawan->kry_level,
            'target_mingguan' => 'Pencapaian Target Penjualan Test Import',
            'tugas_dilakukan' => 'Monitoring dan Followup Pelanggan',
            'hasil' => '100% Target Tercapai',
            'kendala' => 'Tidak Ada',
            'solusi' => 'Optimal',
        ]
    );
    echo "Test KPI Saved for {$karyawan->kry_nama}!\n";
} else {
    echo "No Karyawan found!\n";
}
