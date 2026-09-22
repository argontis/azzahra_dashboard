<?php

require __DIR__.'/../vendor/autoload.php';
$app = require __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Kernel::class);
$kernel->bootstrap();

use Illuminate\Contracts\Http\Kernel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

$app->instance('request', Request::create('/'));

$creds = [
    ['Admin', 'admin123'],
    ['kasir', 'kasir123'],
    ['yanacs', 'cs12345'],
    ['fahmiteknisi', 'teknisi123'],
    ['admin1', 'admin'],
];

foreach ($creds as [$u, $p]) {
    if (Auth::attempt(['kry_username' => $u, 'password' => $p])) {
        $user = Auth::user();
        echo "SUCCESS LOGIN: {$u} as {$user->kry_level} ({$user->kry_nama})\n";
        Auth::logout();
    } else {
        echo "FAILED LOGIN: {$u}\n";
    }
}
