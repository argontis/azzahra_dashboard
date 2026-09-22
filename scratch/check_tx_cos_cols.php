<?php

require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Kernel::class);
$kernel->bootstrap();

use Illuminate\Contracts\Console\Kernel;
use Illuminate\Support\Facades\DB;

echo '--- TRANSAKSI COLUMNS ---'.PHP_EOL;
$cols = DB::select('DESCRIBE transaksi');
foreach ($cols as $c) {
    echo "{$c->Field} ({$c->Type})".PHP_EOL;
}

echo '--- COSTOMER COLUMNS ---'.PHP_EOL;
$cols2 = DB::select('DESCRIBE costomer');
foreach ($cols2 as $c) {
    echo "{$c->Field} ({$c->Type})".PHP_EOL;
}
