<?php

require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Kernel::class);
$kernel->bootstrap();

use Illuminate\Contracts\Console\Kernel;
use Illuminate\Support\Facades\DB;

$cols = DB::getSchemaBuilder()->getColumnListing('karyawan');
echo 'karyawan columns: '.implode(', ', $cols).PHP_EOL;
