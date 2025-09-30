<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = [
            'email' => $request->email,
            'password' => $request->senha
        ];

        if(Auth::attempt($credentials)){
            $usuario = Auth::user();
            $token = $usuario->createToken('auth-token')->plainTextToken;
            return response()->json(['token' => $token, 'usuario' => $usuario], 200);
        }

        return response()->json(['error' => 'Credenciais inválidas!'], 401);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Logout realizado com sucesso!'], 200);
    }
}
