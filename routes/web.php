<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

/*
 * |--------------------------------------------------------------------------
 * | Public Routes
 * |--------------------------------------------------------------------------
 */

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

/*
 * |--------------------------------------------------------------------------
 * | Authenticated Application Routes
 * |--------------------------------------------------------------------------
 */

Route::middleware([
    'auth',
    'verified',
])->group(function () {
    /*
     * Dashboard
     *
     * Business authorization will be added once the final
     * AccessService + subscription/feature middleware pipeline
     * is verified.
     */

    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    /*
     * Profile
     *
     * Profile management is account-level functionality.
     * It should not depend on a business feature or subscription.
     */

    Route::get('/profile', [
        ProfileController::class,
        'edit',
    ])->name('profile.edit');

    Route::patch('/profile', [
        ProfileController::class,
        'update',
    ])->name('profile.update');

    Route::delete('/profile', [
        ProfileController::class,
        'destroy',
    ])->name('profile.destroy');
});

/*
 * |--------------------------------------------------------------------------
 * | Authentication Routes
 * |--------------------------------------------------------------------------
 * |
 * | auth.php contains the application's authentication routes.
 * |
 */

require __DIR__ . '/auth.php';
