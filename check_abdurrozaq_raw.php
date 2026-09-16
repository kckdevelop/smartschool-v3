<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$setting = App\Models\SettingPembayaran::getSetting();
$service = new App\Services\BpdDiyService($setting);

$res = $service->fetchReportTagihanVa(['search' => 'ABDURROZAQ', 'length' => 10]);
echo "BPD DIY response for 'ABDURROZAQ':\n";
print_r($res['data']);
