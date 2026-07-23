<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();
$s = DB::table('sekolah')->first();
if ($s) {
    echo "logo: " . $s->logo . PHP_EOL;
    echo "kop: " . $s->kop . PHP_EOL;
} else {
    echo "null";
}
