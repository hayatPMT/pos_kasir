<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\POSController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PromotionController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\ShiftController;
use App\Http\Controllers\ShiftScheduleController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // POS
    Route::get('/pos', [POSController::class, 'index'])->name('pos.index');
    Route::post('/pos/transaction', [POSController::class, 'store'])->name('pos.store');

    // Master Data
    Route::resource('products', ProductController::class);
    Route::resource('members', MemberController::class);
    Route::resource('branches', BranchController::class);
    Route::resource('users', UserController::class);
    Route::resource('promotions', PromotionController::class);
    Route::resource('transactions', TransactionController::class)->only(['index', 'show']);

    // Reporting
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/export', [ReportController::class, 'exportCsv'])->name('reports.export');

    // Shift Management
    Route::get('/shifts', [ShiftController::class, 'index'])->name('shifts.index');
    Route::post('/shifts/open', [ShiftController::class, 'open'])->name('shifts.open');
    Route::post('/shifts/close', [ShiftController::class, 'close'])->name('shifts.close');
    Route::resource('schedules', ShiftScheduleController::class)->only(['index', 'store', 'destroy']);

    // QRIS
    Route::get('/pos/qris/status/{invoice}', [POSController::class, 'checkPaymentStatus'])->name('pos.qris.status');
    Route::post('/pos/qris/simulate-success', [POSController::class, 'simulatePaymentSuccess'])->name('pos.qris.simulate');

    // Customer Display
    Route::get('/pos/display', [POSController::class, 'customerDisplay'])->name('pos.display');
    Route::get('/pos/display/data', [POSController::class, 'getDisplayData'])->name('pos.display.data');
    Route::post('/pos/display/sync', [POSController::class, 'syncDisplayData'])->name('pos.display.sync');

    Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
    Route::post('/settings', [SettingController::class, 'update'])->name('settings.update');
});
