<?php

use Illuminate\Support\Facades\Route;

Route::get('/{any}', function () {
    return view('layouts.app');
})->where('any', '^(?!api).*$');

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
