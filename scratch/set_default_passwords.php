<?php

require __DIR__.'/../vendor/autoload.php';
$app = require __DIR__.'/../bootstrap/app.php';
$app->make(Kernel::class)->bootstrap();

use App\Models\Karyawan;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Support\Facades\Hash;

$admin = Karyawan::where('kry_username', 'Admin')->first();
if ($admin) {
    $admin->kry_pswd = Hash::make('admin123');
    $admin->save();
    echo "Updated 'Admin' password to admin123\n";
}

$kasir = Karyawan::where('kry_username', 'kasir')->first();
if ($kasir) {
    $kasir->kry_pswd = Hash::make('kasir123');
    $kasir->save();
    echo "Updated 'kasir' password to kasir123\n";
}

$yanacs = Karyawan::where('kry_username', 'yanacs')->first();
if ($yanacs) {
    $yanacs->kry_pswd = Hash::make('cs12345');
    $yanacs->save();
    echo "Updated 'yanacs' password to cs12345\n";
}

$fahmiteknisi = Karyawan::where('kry_username', 'fahmiteknisi')->first();
if ($fahmiteknisi) {
    $fahmiteknisi->kry_pswd = Hash::make('teknisi123');
    $fahmiteknisi->save();
    echo "Updated 'fahmiteknisi' password to teknisi123\n";
}
