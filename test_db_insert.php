<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

use Illuminate\Support\Facades\DB;

try {
    echo "Attempting Insert...\n";
    DB::table('pasiens')->insert([
        'nama_pasien' => 'Test Manual Insert',
        'nik' => '1234567890123456',
        'alamat' => 'Test Address, Sumedang',
        'no_bpjs' => null,
        'poli_tujuan' => 'Umum',
        'tipe_pasien' => 'umum',
        'nomor_antrian' => 'PENDING',
        'status' => 'menunggu_pembayaran',
        'status_pembayaran' => 'pending',
        'created_at' => now(),
        'updated_at' => now(),
    ]);
    echo "SUCCESS: Inserted with PENDING nomor_antrian.\n";
} catch (\Exception $e) {
    echo "FAILURE: " . $e->getMessage() . "\n";
}
