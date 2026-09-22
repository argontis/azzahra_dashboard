<?php

require __DIR__.'/../vendor/autoload.php';
$app = require __DIR__.'/../bootstrap/app.php';
$app->make(Kernel::class)->bootstrap();

use App\Models\Karyawan;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Support\Facades\Hash;

$candidates = ['password', 'admin', 'admin123', '123456', '12345', 'kasir', 'yanacs', 'yanaadmin', '1234'];
$users = Karyawan::all();

foreach ($users as $u) {
    echo "User: {$u->kry_username} ({$u->kry_level})\n";
    foreach ($candidates as $c) {
        if (Hash::check($c, $u->kry_pswd)) {
            echo "  --> MATCH PASSWORD: '{$c}'\n";
            break;
        }
    }
}
