<?php

use Illuminate\Contracts\Console\Kernel;

require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Kernel::class);
$kernel->bootstrap();

echo 'SESSION DRIVER: '.config('session.driver').PHP_EOL;
echo 'SESSION DOMAIN: '.var_export(config('session.domain'), true).PHP_EOL;
echo 'SESSION SECURE: '.var_export(config('session.secure'), true).PHP_EOL;
echo 'SESSION SAME_SITE: '.var_export(config('session.same_site'), true).PHP_EOL;
