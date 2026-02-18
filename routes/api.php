<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::apiResource('transactions', \App\Http\Controllers\Api\TransactionController::class)->only(['index', 'store', 'show']);
});
