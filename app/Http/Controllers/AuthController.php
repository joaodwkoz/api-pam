<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Usuario;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $usuario = Usuario::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password,
            'cep' => $request->cep,
            'numero' => $request->numero,
            'complemento' => $request->complemento,
            'logradouro' => $request->logradouro,
            'bairro' => $request->bairro,
            'cidade' => $request->cidade,
            'estado' => $request->estado,
        ]);

        $token = $usuario->createToken('auth-token')->plainTextToken;

        return response()->json(['token' => $token, 'usuario' => $usuario], 201);
    }

    public function login(Request $request)
    {
        $credentials = [
            'email' => $request->email,
            'password' => $request->password
        ];

        if(Auth::attempt($credentials)){
            $usuario = Auth::user();
            $token = $usuario->createToken('auth-token')->plainTextToken;
            return response()->json(['token' => $token, 'usuario' => $usuario], 200);
        }

        return response()->json(['error' => 'Credenciais inválidas.'], 401);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Logout realizado com sucesso!'], 200);
    }
}
