<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\MeasurementController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentController;

// Dashboard
Route::get('/', [DashboardController::class, 'index'])->name('dashboard.index');

// Resource routes
Route::resource('customers', CustomerController::class);
Route::resource('measurements', MeasurementController::class);
Route::get('orders', [OrderController::class, 'index'])->name('orders.index');
Route::get('orders/create', [OrderController::class, 'create'])->name('orders.create');
Route::post('orders', [OrderController::class, 'store'])->name('orders.store');
Route::get('orders/{order}', [OrderController::class, 'show'])->name('orders.show');
Route::put('orders/{order}', [OrderController::class, 'update'])->name('orders.update');
Route::delete('orders/{order}', [OrderController::class, 'destroy'])->name('orders.destroy');
Route::resource('payments', PaymentController::class);

// Additional routes
Route::get('payments/{payment}/receipt', [PaymentController::class, 'receipt'])->name('payments.receipt');
Route::post('measurements/{measurement}/generate-order', [OrderController::class, 'generateFromMeasurement'])->name('measurements.generate-order');
Route::get('orders/{order}/print', [OrderController::class, 'print'])->name('orders.print');
