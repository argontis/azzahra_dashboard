<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

foreach (['costomer', 'transaksi', 'order_list', 'karyawan', 'tindakan'] as $tbl) {
    echo "=== TABLE: $tbl ===\n";
    $cols = DB::select("DESCRIBE $tbl");
    foreach ($cols as $c) {
        echo "{$c->Field} | {$c->Type} | Null:{$c->Null} | Key:{$c->Key} | Default:{$c->Default} | Extra:{$c->Extra}\n";
    }
    echo "\n";
}
