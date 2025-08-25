<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ConvocationController;
use App\Http\Controllers\Api\MatriculeController;
use App\Http\Controllers\Api\ProcesVerbalController;
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

            //Gestion des convocations
            Route::get('convocations', [ConvocationController::class, 'index']);
            Route::post('create', [ConvocationController::class, 'store']);
            Route::get('convocations/{id}', [ConvocationController::class, 'show']);
            Route::put('convocations/{id}', [ConvocationController::class, 'update']);
            Route::delete('convocations/{id}', [ConvocationController::class, 'destroy']);

            //Gestion des procès-verbaux
            Route::get('proces-verbaux', [ProcesVerbalController::class, 'index']);
            Route::put('proces-verbaux/{id}', [ProcesVerbalController::class, 'update']);
            Route::post('create', [ProcesVerbalController::class, 'store']);
            Route::get('proces-verbaux/{id}', [ProcesVerbalController::class, 'show']);
            Route::delete('proces-verbaux/{id}', [ProcesVerbalController::class, 'destroy']);
        });
    });
});
