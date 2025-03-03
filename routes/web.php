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

    Route::get('pazienti', [PatientController::class, 'index'])->name('patients.index');
    Route::get('pazienti/crea', [PatientController::class, 'create'])->name('patients.create');
    Route::post('pazienti', [PatientController::class, 'store'])->name('patients.store');
    Route::get('pazienti/{patient}', [PatientController::class, 'show'])->name('patients.show');
    Route::put('pazienti/{patient}/modifica', [PatientController::class, 'update'])->name('patients.update')
        ->middleware([HandlePrecognitiveRequests::class]);
    Route::delete('pazienti/{patient}', [PatientController::class, 'destroy'])->name('patients.destroy');
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
