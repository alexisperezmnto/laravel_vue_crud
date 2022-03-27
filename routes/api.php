<?php

use App\Http\Controllers\UserController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ServiceController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

//Public routes
Route::post('/register', [UserController::class, 'register']);
Route::post('/login', [UserController::class, 'login']);

Route::group(['middleware' => ['auth:sanctum']], function() {
    Route::get('/clientes', [CustomerController::class, 'index']);
    Route::get('/clientes/{id}', [CustomerController::class, 'show']);
    Route::post('/clientes', [CustomerController::class, 'store']);
    Route::patch('/clientes/{id}', [CustomerController::class, 'update']);
    Route::delete('/clientes/{id}', [CustomerController::class, 'destroy']);

    Route::get('/servicios', [ServiceController::class, 'index']);

    Route::post('/logout', [UserController::class, 'logout']);
});
