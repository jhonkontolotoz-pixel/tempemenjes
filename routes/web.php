<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Apps\CustomerController;
use App\Http\Controllers\Apps\POSController;
use App\Http\Controllers\Apps\ProductController;
use App\Http\Controllers\Apps\ReportController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

// ================================
// HOME → Dashboard
// ================================
Route::get('/', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// ================================
// AUTH ROUTES
// ================================
Route::middleware('auth')->group(function () {

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // ================================
    // CUSTOMERS — Admin & Manager only
    // ================================
    Route::middleware('role:admin,manager')->group(function () {
        Route::get('/customers', [CustomerController::class, 'index'])->name('customers.index');
        Route::get('/customers/create', [CustomerController::class, 'create'])->name('customers.create');
        Route::get('/customers/{customer}/edit', [CustomerController::class, 'edit'])->name('customers.edit');
        Route::post('/customers', [CustomerController::class, 'store'])->name('customers.store');
        Route::patch('/customers/{customer}', [CustomerController::class, 'update'])->name('customers.update');
        Route::delete('/customers/{customer}', [CustomerController::class, 'destroy'])->name('customers.destroy');
    });

    // ================================
    // PRODUCTS — Admin & Manager only
    // ================================
    Route::middleware('role:admin,manager')->group(function () {
        Route::get('/products', [ProductController::class, 'index'])->name('products.index');
        Route::post('/products', [ProductController::class, 'store'])->name('products.store');
        Route::patch('/products/{product}', [ProductController::class, 'update'])->name('products.update');
        Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');
    });

    // ================================
    // REPORTS — Admin & Manager only
    // ================================
    Route::middleware('role:admin,manager')->group(function () {
        Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    });

    // ================================
    // POS — Admin & Kasir only
    // ================================
    Route::middleware('role:admin,kasir')->group(function () {
        Route::get('/pos', [POSController::class, 'index'])->name('pos.index');
        Route::post('/pos/checkout', [POSController::class, 'checkout'])->name('pos.checkout');
    });

});

require __DIR__ . '/auth.php';