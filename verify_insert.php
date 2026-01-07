<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$kernel->handle(Illuminate\Http\Request::capture());

use Illuminate\Support\Facades\DB;

try {
    echo "1. Testing RAW SQL Insert...\n";
    DB::statement("INSERT INTO pasiens (nama_pasien, nik, alamat, poli_tujuan, tipe_pasien, nomor_antrian, status, created_at, updated_at) VALUES ('Raw Test', '1111111111111111', 'Addr', 'Umum', 'umum', 'PENDING', 'menunggu_pembayaran', datetime('now'), datetime('now'))");
    echo "RAW SQL SUCCESS.\n";
} catch (\Exception $e) {
    echo "RAW SQL FAILED: " . $e->getMessage() . "\n";
}

try {
    echo "2. Testing Query Builder Insert...\n";
    DB::table('pasiens')->insert([
        'nama_pasien' => 'Builder Test',
        'nik' => '2222222222222222',
        'alamat' => 'Addr',
        'poli_tujuan' => 'Umum',
        'tipe_pasien' => 'umum',
        'nomor_antrian' => 'PENDING',
        'status' => 'menunggu_pembayaran',
        'created_at' => now(),
        'updated_at' => now(),
    ]);
    echo "BUILDER SUCCESS.\n";
} catch (\Exception $e) {
    echo "BUILDER FAILED: " . $e->getMessage() . "\n";
}
