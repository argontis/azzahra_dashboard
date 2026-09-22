<?php

require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Kernel::class);
$kernel->bootstrap();

use Illuminate\Contracts\Console\Kernel;
use Illuminate\Support\Facades\DB;

$cols = DB::select('DESCRIBE sessions');
foreach ($cols as $c) {
    echo "{$c->Field} | {$c->Type} | Null: {$c->Null}".PHP_EOL;
}
