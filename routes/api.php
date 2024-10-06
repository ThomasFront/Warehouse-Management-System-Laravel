<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\CheckAdminRole;
use App\Http\Middleware\CheckCurrentUser;
use App\Http\Middleware\CheckMessageOwner;
use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => 'auth'
], function () {
    Route::post('register', [AuthController::class, 'register'])->middleware('auth:api', CheckAdminRole::class);
    Route::post('login', [AuthController::class, 'login']);
    Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:api');
    Route::post('refresh', [AuthController::class, 'refresh'])->middleware('auth:api');
    Route::get('me', [AuthController::class, 'me'])->middleware('auth:api');
});

Route::group([
    'prefix' => 'categories',
    'middleware' => 'auth:api'
], function () {
    Route::get('', [CategoryController::class, 'index']);
    Route::post('', [CategoryController::class, 'store']);
    Route::get('/dropdown', [CategoryController::class, 'dropdownProvider']);
    Route::get('{category}', [CategoryController::class, 'show']);
    Route::delete('{category}', [CategoryController::class, 'destroy']);
    Route::patch('{category}', [CategoryController::class, 'update']);
});

Route::group([
    'prefix' => 'users',
    'middleware' => 'auth:api'
], function () {
    Route::get('', [UserController::class, 'index']);
    Route::get('{user}', [UserController::class, 'show']);
    Route::post('avatar', [UserController::class, 'uploadAvatar']);
    Route::delete('{user}', [UserController::class, 'destroy'])->middleware(CheckAdminRole::class);
    Route::patch('{user}', [UserController::class, 'update'])->middleware(CheckAdminRole::class);
    Route::patch('{user}/profile', [UserController::class, 'editUserProfile'])->middleware(CheckCurrentUser::class);
});

Route::group([
    'prefix' => 'messages',
    'middleware' => 'auth:api'
], function () {
    Route::get('', [MessageController::class, 'index']);
    Route::post('', [MessageController::class, 'store']);
    Route::delete('{message}', [MessageController::class, 'destroy'])->middleware(CheckMessageOwner::class);
    Route::patch('{message}', [MessageController::class, 'update'])->middleware(CheckMessageOwner::class);
});

Route::group([
    'prefix' => 'products',
    'middleware' => 'auth:api'
], function () {
    Route::get('', [ProductController::class, 'index']);
    Route::get('/dropdown', [ProductController::class, 'dropdownProvider']);
    Route::get('/export', [ProductController::class, 'exportCsv']);
    Route::get('{product}', [ProductController::class, 'show']);
    Route::post('image', [ProductController::class, 'uploadProductImage']);
    Route::post('', [ProductController::class, 'store']);
    Route::delete('{product}', [ProductController::class, 'destroy']);
    Route::patch('{product}', [ProductController::class, 'update']);
});

Route::group([
    'prefix' => 'sales',
    'middleware' => 'auth:api'
], function () {
    Route::get('', [SaleController::class, 'index']);
    Route::get('/export', [SaleController::class, 'exportCsv']);
    Route::post('', [SaleController::class, 'store']);
});

Route::get('dashboard', [DashboardController::class, 'index'])->middleware('auth:api');
Route::get('menu', [MenuController::class, 'index'])->middleware('auth:api');
