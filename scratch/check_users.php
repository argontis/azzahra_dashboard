<?php

require __DIR__.'/../vendor/autoload.php';
$app = require __DIR__.'/../bootstrap/app.php';
$app->make(Kernel::class)->bootstrap();

use App\Models\Customer;
use App\Models\Karyawan;
use App\Models\Produk;
use App\Models\Transaksi;
use Illuminate\Contracts\Console\Kernel;

echo 'Total Karyawan: '.Karyawan::count()."\n";
echo 'Total Customer: '.Customer::count()."\n";
echo 'Total Transaksi: '.Transaksi::count()."\n";
echo 'Total Produk: '.Produk::count()."\n";
