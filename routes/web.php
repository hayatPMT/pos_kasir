<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\POSController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PromotionController;
use App\Http\Controllers\SettingController;
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

    Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
    Route::post('/settings', [SettingController::class, 'update'])->name('settings.update');
});
