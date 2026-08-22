<?php

require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Kernel::class);
$kernel->bootstrap();

use App\Models\Karyawan;
use Illuminate\Contracts\Console\Kernel;

$rfid = '0008168066';
$rfidClean = ltrim($rfid, '0');

$karyawan = Karyawan::where('kry_kode', $rfid)
    ->orWhere('kry_kode', $rfidClean)
    ->orWhere('kry_username', $rfid)
    ->orWhere('kry_username', $rfidClean)
    ->orWhere('kry_nama', 'LIKE', "%{$rfid}%")
    ->first();

if ($karyawan) {
    echo "Found Karyawan: {$karyawan->kry_nama} (Level: {$karyawan->kry_level}, RFID: {$karyawan->kry_username})\n";
} else {
    echo "Not found!\n";
}
