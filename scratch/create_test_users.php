<?php

require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Kernel::class);
$kernel->bootstrap();

use App\Models\Karyawan;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

$columns = DB::getSchemaBuilder()->getColumnListing('karyawan');
echo "Columns in 'karyawan' table: ".implode(', ', $columns).PHP_EOL;

$users = [
    [
        'username' => 'testadmin',
        'nama' => 'Test Admin',
        'level' => 'Admin',
    ],
    [
        'username' => 'testhr',
        'nama' => 'Test HR',
        'level' => 'HR',
    ],
    [
        'username' => 'testcs',
        'nama' => 'Test Customer Service',
        'level' => 'Customer Service',
    ],
    [
        'username' => 'testkasir',
        'nama' => 'Test Kasir',
        'level' => 'Kasir',
    ],
    [
        'username' => 'testteknisi',
        'nama' => 'Test Teknisi',
        'level' => 'Teknisi',
    ],
];

foreach ($users as $u) {
    $k = Karyawan::where('kry_username', $u['username'])->first();
    if (! $k) {
        $k = new Karyawan;
        $k->kry_username = $u['username'];
    }
    $k->kry_nama = $u['nama'];
    $k->kry_level = $u['level'];
    $k->kry_pswd = Hash::make('password123');

    if (in_array('kry_nik', $columns)) {
        $k->kry_nik = 'TEST-'.strtoupper($u['username']);
    }
    if (in_array('kry_tempat', $columns)) {
        $k->kry_tempat = 'Tegal';
    }
    if (in_array('kry_tgl_lahir', $columns)) {
        $k->kry_tgl_lahir = '2000-01-01';
    }
    if (in_array('kry_tlp', $columns)) {
        $k->kry_tlp = '081234567890';
    }
    if (in_array('kry_telp', $columns)) {
        $k->kry_telp = '081234567890';
    }
    if (in_array('kry_alamat', $columns)) {
        $k->kry_alamat = 'Azzahra Computer';
    }
    if (in_array('kry_tgl_masuk', $columns)) {
        $k->kry_tgl_masuk = date('Y-m-d');
    }
    if (in_array('kry_tgl_keluar', $columns)) {
        $k->kry_tgl_keluar = null;
    }
    if (in_array('kry_join_date', $columns)) {
        $k->kry_join_date = date('Y-m-d');
    }
    if (in_array('kry_status', $columns)) {
        $k->kry_status = 1;
    }
    if (in_array('kry_foto', $columns)) {
        $k->kry_foto = 'default.jpg';
    }

    $k->save();
    echo "BERHASIL: Username '{$k->kry_username}' | Role: '{$k->kry_level}' | Kode: '{$k->kry_kode}'".PHP_EOL;
}
