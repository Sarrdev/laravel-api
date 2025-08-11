<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\MatriculeController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('auth')->group(function () {

    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('logout', [AuthController::class, 'logout']);
        Route::get('profile', [AuthController::class, 'profile']);
        Route::get('me', [AuthController::class, 'me']);

        Route::middleware('admin')->group(function () {
            Route::get('users', [AuthController::class, 'index']);
            Route::get('users/{id}', [AuthController::class, 'show']);
            Route::put('users/{id}', [AuthController::class, 'update']);
            Route::delete('users/{id}', [AuthController::class, 'destroy']);
            Route::put('/suspend/{user}', [AuthController::class, 'suspendUser']);
            Route::put('/reactivate/{user}', [AuthController::class, 'reactivateUser']);
        });
    });

});
