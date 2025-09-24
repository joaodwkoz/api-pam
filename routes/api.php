<?php

use App\Http\Controllers\UsuarioController;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/login', [UsuarioController::class, 'login']);
Route::post('/usuario', [UsuarioController::class, 'store']);
Route::get('/usuario/{usuario}', [UsuarioController::class, 'show']);
Route::put('/usuario/{usuario}', [UsuarioController::class, 'update']);