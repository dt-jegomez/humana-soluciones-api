<?php

use App\Http\Controllers\PropertyController;
use Illuminate\Support\Facades\Route;

Route::prefix('properties')->group(function () {
    Route::get('/', [PropertyController::class, 'index']);
    Route::post('/', [PropertyController::class, 'store']);
    Route::get('{property}', [PropertyController::class, 'show']);
    Route::put('{property}', [PropertyController::class, 'update']);
    Route::delete('{property}', [PropertyController::class, 'destroy']);
});
