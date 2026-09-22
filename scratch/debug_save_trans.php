<?php

require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Customer;
use App\Models\OrderList;
use App\Models\Transaksi;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

try {
    DB::beginTransaction();
    $id_costomer = 'TTS260922999';
    $trans_kode = 'TR220926999';

    echo '1. Creating customer...'.PHP_EOL;
    $c = Customer::create([
        'id_costomer' => $id_costomer,
        'cos_nama' => 'Test Debug',
        'username' => $id_costomer,
        'password' => Hash::make($id_costomer),
        'cos_alamat' => 'Tegal',
        'cos_hp' => '081234567890',
        'cos_pswd_type' => 'pin',
        'cos_pswd' => '123456',
        'cos_tanggal' => date('Y-m-d'),
        'cos_jam' => date('H:i:s'),
    ]);
    echo 'Customer created OK.'.PHP_EOL;

    echo '2. Creating transaksi...'.PHP_EOL;
    $t = Transaksi::create([
        'cos_kode' => $id_costomer,
        'trans_total' => 0,
        'trans_discount' => 0,
        'trans_status' => 'Baru',
        'trans_tanggal' => date('Y-m-d'),
        'cos_tanggal' => date('Y-m-d'),
        'cos_jam' => date('H:i:s'),
    ]);
    $trans_kode = (string) $t->trans_kode;
    echo "Transaksi created OK with trans_kode: $trans_kode.".PHP_EOL;

    echo '3. Creating OrderList...'.PHP_EOL;
    $o = OrderList::create([
        'trans_kode' => $trans_kode,
        'cos_kode' => $id_costomer,
        'trans_total' => 0,
        'trans_discount' => 0,
        'trans_tanggal' => date('Y-m-d'),
        'trans_status' => 'itemSubmitted',
        'merek' => 'Asus',
        'device' => 'Laptop',
        'status_garansi' => 'OOW',
        'seri' => 'ROG',
        'ket_keluhan' => 'Test keluhan',
        'email' => 'example@gmail.com',
        'alamat' => 'Tegal',
    ]);
    echo 'OrderList created OK.'.PHP_EOL;

    DB::rollBack();
    echo 'ALL 3 INSERTIONS SUCCEEDED!'.PHP_EOL;
} catch (Throwable $e) {
    DB::rollBack();
    echo 'ERROR: '.$e->getMessage().' at '.$e->getFile().':'.$e->getLine().PHP_EOL;
}
