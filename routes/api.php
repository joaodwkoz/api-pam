<?php

use App\Http\Controllers\AlimentoController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RefeicaoController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ImcController;
use App\Http\Controllers\AlergiaController;

Route::post('/auth/register', [AuthController::class, 'register']);
Route::post('/auth/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/auth/logout', [AuthController::class, 'logout']);

    Route::get('/usuario', function (Request $request) {
        return $request->user();
    });

    Route::get('/usuario/{usuario}', [UsuarioController::class, 'show']);
    Route::put('/usuario/{usuario}', [UsuarioController::class, 'update']);
    
    Route::get('/alimentos/search', [AlimentoController::class, 'search']);

    Route::get('/refeicoes/{date}', [RefeicaoController::class, 'getRefeicoesByDate']);
    Route::post('/refeicoes', [RefeicaoController::class, 'storeAPI']);

    Route::get('/imc', [ImcController::class, 'index']); 
    Route::post('/imc', [ImcController::class, 'store']);

    Route::get('/alergias', [AlergiaController::class, 'index']);     
    Route::post('/alergias', [AlergiaController::class, 'store']);     
    Route::delete('/alergias/{alergia}', [AlergiaController::class, 'destroy']); 
});