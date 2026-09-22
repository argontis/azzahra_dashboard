<?php

require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Contracts\Console\Kernel;
use Illuminate\Support\Facades\DB;

$cols = DB::select("SHOW COLUMNS FROM costomer LIKE 'cos_pswd_type'");
print_r($cols);

DB::statement("ALTER TABLE costomer MODIFY cos_pswd_type VARCHAR(50) NULL DEFAULT 'text'");
echo 'cos_pswd_type updated to VARCHAR(50)'.PHP_EOL;
