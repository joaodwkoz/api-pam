<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UsuarioController extends Controller
{
    public function login(Request $request){
        $usuario = Usuario::where('email', $request->email)->first();

        if(!Hash::check($request->senha, $usuario->senha)){
            return response()->json(['erro' => 'Credenciais inválidas'], 401);
        }

        return response()->json(['sucesso' => 'Credencias válidas', 'usuario'=> $usuario], 200);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $usuario = new Usuario();

            $usuario->nome = $request->nome;
            $usuario->email = $request->email;
            $usuario->senha = Hash::make($request->senha);

            $img = $request->file('fotoPerfil');

            if (is_null($img)) {
                $path = "";
            } else {
                $path = $img->store('imgsFotoPerfil', 'public');
            }

            $usuario->fotoPerfil = $path;
            $usuario->cep = $request->cep;
            $usuario->numero = $request->numero;
            $usuario->complemento = $request->complemento;
            $usuario->bairro = $request->bairro;
            $usuario->cidade = $request->cidade;
            $usuario->estado = $request->estado;
            $usuario->logradouro = $request->logradouro;

            $usuario->save();

            // Retorna o usuário criado em JSON, com status 201 (created)
            return response()->json([
                'success' => true,
                'usuario' => $usuario
            ], 201);

        } catch (\Exception $e) {
            // Retorna erro em JSON, status 500
            return response()->json([
                'success' => false,
                'message' => 'Erro ao criar usuário',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Usuario $usuario)
    {
        return response()->json($usuario);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Usuario $usuario)
    {
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Usuario $usuario)
    {
        $usuario->update($request->all());

        $usuario->save();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Usuario $usuario)
    {
        //
    }
}
