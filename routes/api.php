<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\MenuController;
use App\Http\Middleware\CheckAdminRole;
use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => 'auth',
], function () {
    Route::post('register', [AuthController::class, 'register'])->middleware('auth:api', CheckAdminRole::class);
    Route::post('login', [AuthController::class, 'login']);
    Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:api');
    Route::post('refresh', [AuthController::class, 'refresh'])->middleware('auth:api');
    Route::get('me', [AuthController::class, 'me'])->middleware('auth:api');
});

Route::group([
    'prefix' => 'categories',
], function () {
    Route::get('', [CategoryController::class, 'index'])->middleware('auth:api');
    Route::post('', [CategoryController::class, 'store'])->middleware('auth:api');
    Route::get('{category}', [CategoryController::class, 'show'])->middleware('auth:api');
    Route::delete('{category}', [CategoryController::class, 'destroy'])->middleware('auth:api');
    Route::patch('{category}', [CategoryController::class, 'update'])->middleware('auth:api');
});

Route::get('menu', [MenuController::class, 'index'])->middleware('auth:api');
