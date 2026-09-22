<?php

require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Kernel::class);
$kernel->bootstrap();

use Illuminate\Contracts\Console\Kernel;
use Illuminate\Support\Facades\DB;

echo 'Tables in DB:'.PHP_EOL;
$tables = DB::select('SHOW TABLES');
foreach ($tables as $t) {
    echo array_values((array) $t)[0].PHP_EOL;
}
