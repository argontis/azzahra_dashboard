<?php

require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Kernel::class);
$kernel->bootstrap();

use App\Models\Karyawan;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Support\Facades\Hash;

Karyawan::updateOrCreate(
    ['kry_username' => '0008168066'],
    [
        'kry_pswd' => Hash::make('123456'),
        'kry_nama' => 'Budi Santoso (Magang)',
        'kry_level' => 'Magang / PKL',
        'kry_telp' => '081234567890',
        'kry_alamat' => 'Jl. Ahmad Yani No. 12, Tegal',
        'kry_status' => 1,
        'kry_join_date' => date('Y-m-d'),
    ]
);

Karyawan::updateOrCreate(
    ['kry_username' => '0009271543'],
    [
        'kry_pswd' => Hash::make('123456'),
        'kry_nama' => 'Siti Rahmawati (PKL)',
        'kry_level' => 'Magang / PKL',
        'kry_telp' => '085712345678',
        'kry_alamat' => 'Jl. Gajah Mada No. 45, Tegal',
        'kry_status' => 1,
        'kry_join_date' => date('Y-m-d'),
    ]
);

echo "Data Magang / PKL berhasil diisikan!\n";
