<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$kernel->handle(Illuminate\Http\Request::capture());
use Illuminate\Support\Facades\DB;

$cols = DB::select("PRAGMA table_info(pasiens)");
foreach($cols as $c) {
    if ($c->name === 'nomor_antrian') {
        print_r($c);
    }
}
