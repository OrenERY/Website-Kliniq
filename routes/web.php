<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PasienController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Halaman Utama: Pendaftaran
Route::get('/', [PasienController::class, 'index'])->name('pendaftaran.index');

// Proses Simpan Pendaftaran
Route::post('/pendaftaran', [PasienController::class, 'store'])->name('pendaftaran.store');

// Halaman Antrian
Route::get('/antrian', [PasienController::class, 'showQueue'])->name('queue.show');

// Redirect /dashboard atau lainnya jika perlu, sementara kita fokus ke tugas
// Route::get('/dashboard', function () { return view('dashboard'); });

require __DIR__.'/settings.php';
