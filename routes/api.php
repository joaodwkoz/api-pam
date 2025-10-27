<?php

use App\Http\Controllers\AlimentoController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RefeicaoController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ImcController;
use App\Http\Controllers\AlergiaController;
use App\Http\Controllers\ConsumoController;
use App\Http\Controllers\CopoController;
use App\Http\Controllers\IconeController;
use App\Http\Controllers\GlicemiaController;
use App\Http\Controllers\BatimentoController;


Route::post('/auth/register', [AuthController::class, 'register']);
Route::post('/auth/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/auth/logout', [AuthController::class, 'logout']);

    Route::get('/usuario', function (Request $request) {
        return $request->user();
    });

    Route::get('/usuario/{usuario}', [UsuarioController::class, 'show']);
    Route::put('/usuario/{usuario}', [UsuarioController::class, 'update']);

    Route::get('/usuario/{usuario}/copos', [CopoController::class, 'showByUser']);

    Route::get('/usuario/{usuario}/consumos', [ConsumoController::class, 'showByUser']);
    Route::get('/usuario/{usuario}/consumos-por-data', [ConsumoController::class, 'showByUserByDate']);
    Route::get('/usuario/{usuario}/consumos-por-periodo', [ConsumoController::class, 'getDetailedHistory']);

    Route::get('/usuario/{usuario}/alergias', [AlergiaController::class, 'showByUser']);
    
    Route::get('/alimentos/search', [AlimentoController::class, 'search']);

    Route::get('/refeicoes/{date}', [RefeicaoController::class, 'getRefeicoesByDate']);
    Route::post('/refeicoes', [RefeicaoController::class, 'storeAPI']);

    Route::get('/imc', [ImcController::class, 'index']); 
    Route::post('/imc', [ImcController::class, 'store']);

    Route::get('/alergias', [AlergiaController::class, 'index']);     
    Route::post('/alergias', [AlergiaController::class, 'store']);
    Route::put('/alergias/{alergia}', [AlergiaController::class, 'update']); 
    Route::delete('/alergias/{alergia}', [AlergiaController::class, 'destroy']); 

    Route::get('/copos', [CopoController::class, 'index']);
    Route::get('/copos/{copo}', [CopoController::class, 'show']);
    Route::post('/copo', [CopoController::class, 'store']);
    Route::put('/copos/{copo}', [CopoController::class, 'update']);
    Route::delete('/copos/{copo}', [CopoController::class, 'destroy']);

    Route::get('/icones', [IconeController::class, 'index']);

    Route::post('/consumo', [ConsumoController::class, 'store']);

    Route::apiResource('glicemias', GlicemiaController::class);

    Route::apiResource('batimentos', BatimentoController::class);
});



