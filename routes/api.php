<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\PoliController;
use App\Http\Controllers\API\PatientController;
use App\Http\Controllers\API\QueueController;
use App\Http\Controllers\API\DoctorController;
use App\Http\Controllers\API\ScheduleController;
use App\Http\Controllers\API\MedicalRecordController;
use App\Http\Controllers\API\DashboardController;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Public routes
Route::post('/login', [App\Http\Controllers\AuthController::class, 'login']);

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [App\Http\Controllers\AuthController::class, 'logout']);

    // Poli routes
    Route::apiResource('poli', PoliController::class);

    // Patient routes
    Route::apiResource('patients', PatientController::class);
    Route::get('patients/search/{keyword}', [PatientController::class, 'search']);

    // Doctor routes
    Route::apiResource('doctors', DoctorController::class);

    // Schedule routes
    Route::apiResource('schedules', ScheduleController::class);

    // Queue routes
    Route::apiResource('queues', QueueController::class);
    Route::post('queues/call-next', [QueueController::class, 'callNext']);
    Route::get('queues/current', [QueueController::class, 'currentQueue']);

    // Medical Record routes
    Route::apiResource('medical-records', MedicalRecordController::class);

    // Dashboard routes
    Route::get('dashboard/stats', [DashboardController::class, 'getStats']);
    Route::get('dashboard/today-queues', [DashboardController::class, 'getTodayQueues']);
});