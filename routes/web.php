<?php

use App\Http\Controllers\CustomerController;
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

    Route::middleware('tenant.user')->group(function () {
        Route::get('/customers', [
            CustomerController::class,
            'index',
        ])
            ->middleware('access:customers.view,customer-management')
            ->name('customers.index');

        Route::get('/customers/create', [
            CustomerController::class,
            'create',
        ])
            ->middleware('access:customers.create,customer-management')
            ->name('customers.create');

        Route::post('/customers', [
            CustomerController::class,
            'store',
        ])
            ->middleware('access:customers.create,customer-management')
            ->name('customers.store');

        Route::get('/customers/{customer}', [
            CustomerController::class,
            'show',
        ])
            ->middleware('access:customers.view,customer-management')
            ->name('customers.show');

        Route::get('/customers/{customer}/edit', [
            CustomerController::class,
            'edit',
        ])
            ->middleware('access:customers.update,customer-management')
            ->name('customers.edit');

        Route::put('/customers/{customer}', [
            CustomerController::class,
            'update',
        ])
            ->middleware('access:customers.update,customer-management')
            ->name('customers.update');

        Route::patch('/customers/{customer}', [
            CustomerController::class,
            'update',
        ])
            ->middleware('access:customers.update,customer-management');

        Route::delete('/customers/{customer}', [
            CustomerController::class,
            'destroy',
        ])
            ->name('customers.destroy');
    });

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

require __DIR__.'/auth.php';
