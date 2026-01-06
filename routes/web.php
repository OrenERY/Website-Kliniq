<?php

use App\Http\Controllers\PasienController;
use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('onboarding');
})->name('landing');

Route::get('/pendaftaran', [PasienController::class, 'index'])->name('pendaftaran.index');
Route::post('/pendaftaran', [PasienController::class, 'store'])->name('pendaftaran.store');
Route::get('/antrian', [PasienController::class, 'showQueue'])->name('queue.show');

Route::prefix('admin')->group(function () {
    Route::get('/login', [App\Http\Controllers\AdminController::class, 'showLoginForm'])->name('admin.login');
    Route::post('/login', [App\Http\Controllers\AdminController::class, 'login'])->name('admin.login.submit');
    Route::post('/logout', [App\Http\Controllers\AdminController::class, 'logout'])->name('admin.logout');

    Route::get('/', [App\Http\Controllers\AdminController::class, 'index'])->name('admin.dashboard');
    Route::patch('/assign/{id}', [App\Http\Controllers\AdminController::class, 'assignDoctor'])->name('admin.assign');
});

require __DIR__.'/settings.php';
