<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BoiteIdeeController;
use App\Http\Controllers\Api\ConvocationController;
use App\Http\Controllers\Api\MatriculeController;
use App\Http\Controllers\Api\ProcesVerbalController;
use App\Http\Controllers\Api\NoteServiceController;
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
            Route::post('proces-verbal', [ProcesVerbalController::class, 'store']);
            Route::get('proces-verbaux/{id}', [ProcesVerbalController::class, 'show']);
            Route::delete('proces-verbaux/{id}', [ProcesVerbalController::class, 'destroy']);

            //Gestion des notes de service
            Route::get('note-services', [NoteServiceController::class, 'index']);
            Route::put('note-service/{id}', [NoteServiceController::class, 'update']);
            Route::post('note-service', [NoteServiceController::class, 'store']);
            Route::get('note-service/{id}', [NoteServiceController::class, 'show']);
            Route::delete('note-service/{id}', [NoteServiceController::class, 'destroy']);

            Route::get('/boite-idees', [BoiteIdeeController::class, 'index']);
            Route::get('/boite-idees/{id}', [BoiteIdeeController::class, 'show']);
            Route::patch('/boite-idees/{id}/statut', [BoiteIdeeController::class, 'updateStatut']);
        });

        Route::middleware('user')->group(function () {
            Route::put('users/{id}', [AuthController::class, 'update']);
            Route::get('convocations', [ConvocationController::class, 'index']);
            Route::get('proces-verbaux', [ProcesVerbalController::class, 'index']);
            Route::get('note-services', [NoteServiceController::class, 'index']);

            //gestion boite à idées
            Route::post('/boite-idees', [BoiteIdeeController::class, 'store']);
            Route::get('/boite-idees', [BoiteIdeeController::class, 'index']);
            Route::put('/boite-idees/{id}', [BoiteIdeeController::class, 'update']);
            Route::get('/boite-idees/{id}', [BoiteIdeeController::class, 'show']);
            Route::delete('/boite-idees/{id}', [BoiteIdeeController::class, 'destroy']);
            Route::patch('/boite-idees/{id}/statut', [BoiteIdeeController::class, 'updateStatut']);
        });
    });
});
