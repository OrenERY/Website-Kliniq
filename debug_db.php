<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$kernel->handle(Illuminate\Http\Request::capture());

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;

echo "DB Connection: " . Config::get('database.default') . "\n";
echo "DB File: " . Config::get('database.connections.sqlite.database') . "\n";

$columns = DB::select('PRAGMA table_info(pasiens)');
foreach ($columns as $col) {
    echo "Column: {$col->name} | Type: {$col->type} | NotNull: {$col->notnull} | Default: {$col->dflt_value}\n";
}
