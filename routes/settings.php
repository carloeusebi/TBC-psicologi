<?php

declare(strict_types=1);

use App\Http\Controllers\Settings\PasswordController;
use App\Http\Controllers\Settings\ProfileController;
use App\Http\Controllers\Settings\SubscriptionController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::middleware('auth')->group(function (): void {
    Route::redirect('impostazioni', 'impostazioni/profilo');

    Route::get('impostazioni/profilo', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('impostazioni/profilo', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('impostazioni/profilo', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('impostazioni/password', [PasswordController::class, 'edit'])->name('password.edit');
    Route::put('impostazioni/password', [PasswordController::class, 'update'])->name('password.update');

    Route::get('impostazioni/tema', function () {
        return Inertia::render('settings/Appearance');
    })->name('appearance');

    Route::middleware(['verified'])->group(function (): void {
        Route::get('impostazioni/abbonamento', [SubscriptionController::class, 'index'])->name('subscription');
        Route::get('impostazioni/abbonamento/gestisci', [SubscriptionController::class, 'show'])->name('subscription.show');
        Route::get('impostazioni/abbonamento/modifica', [SubscriptionController::class, 'edit'])->name('subscription.edit');
    });
});
