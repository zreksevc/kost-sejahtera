<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\TenantController;
use App\Http\Controllers\RentalController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\PublicController;

// ─── PUBLIC ROUTES ─────────────────────────────────────────────
Route::get('/', [PublicController::class, 'home'])->name('home');
Route::get('/kamar', [PublicController::class, 'rooms'])->name('public.rooms');

// ─── AUTH ROUTES ───────────────────────────────────────────────
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// ─── ADMIN ROUTES (protected by auth middleware) ───────────────
Route::middleware(['auth'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Rooms
    Route::resource('rooms', RoomController::class);

    // Tenants
    Route::resource('tenants', TenantController::class);
    Route::post('/tenants/{tenant}/checkout', [TenantController::class, 'checkout'])->name('tenants.checkout');

    // Rentals
    Route::resource('rentals', RentalController::class);
    Route::get('/rentals/{rental}/extend', [RentalController::class, 'showExtend'])->name('rentals.extend');
    Route::post('/rentals/{rental}/extend', [RentalController::class, 'extend'])->name('rentals.extend.store');
    Route::post('/rentals/{rental}/terminate', [RentalController::class, 'terminate'])->name('rentals.terminate');

    // Payments
    Route::resource('payments', PaymentController::class);
    Route::get('/payments/{payment}/invoice', [PaymentController::class, 'invoice'])->name('payments.invoice');
    Route::get('/payments/{payment}/pdf', [PaymentController::class, 'downloadPdf'])->name('payments.pdf');
    Route::post('/payments/{payment}/paid', [PaymentController::class, 'markPaid'])->name('payments.paid');
    Route::get('/payments/export/csv', [PaymentController::class, 'exportCsv'])->name('payments.export');

    // Expenses
    Route::get('/expenses', [ExpenseController::class, 'index'])->name('expenses.index');
    Route::post('/expenses', [ExpenseController::class, 'store'])->name('expenses.store');
    Route::delete('/expenses/{expense}', [ExpenseController::class, 'destroy'])->name('expenses.destroy');

    // Reports
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
});
