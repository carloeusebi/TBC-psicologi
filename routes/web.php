<?php

declare(strict_types=1);

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PatientController;
use Illuminate\Foundation\Http\Middleware\HandlePrecognitiveRequests;
use Illuminate\Support\Facades\Route;

Route::get('/', HomeController::class)->name('home');

Route::middleware(['auth', 'verified'])->group(function (): void {
    Route::get('dashboard', DashboardController::class)->name('dashboard');

    Route::middleware(HandlePrecognitiveRequests::class)->group(function (): void {
        Route::apiResource('pazienti', PatientController::class)->parameter('pazienti', 'patient')->names('patients');
    });
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
