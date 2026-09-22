<?php

require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Kernel::class);
$kernel->bootstrap();

use Illuminate\Contracts\Console\Kernel;
use Illuminate\Support\Facades\Auth;

$testAccounts = [
    'testadmin' => 'password123',
    'testhr' => 'password123',
    'testcs' => 'password123',
    'testkasir' => 'password123',
    'testteknisi' => 'password123',
];

foreach ($testAccounts as $user => $pass) {
    $attempt = Auth::attempt(['kry_username' => $user, 'password' => $pass]);
    if ($attempt) {
        $k = Auth::user();
        echo "LOGIN SUCCESS: [{$user}] Role: {$k->kry_level} (Kode: {$k->kry_kode})".PHP_EOL;
        Auth::logout();
    } else {
        echo "LOGIN FAILED: [{$user}]".PHP_EOL;
    }
}
