<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UsuarioController extends Controller
{
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

            $usuario->nome = $request->name;
            $usuario->email = $request->email;
            $usuario->senha = Hash::make($request->password);

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

            return response()->json([
                'success' => true,
                'usuario' => $usuario
            ], 201);

        } catch (\Exception $e) {
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
        try {
            $usuario->fill($request->except(['password', 'fotoPerfil']));

            if($request->filled('senha')){
                $usuario->senha = Hash::make($request->password);
            }

            if($request->hasFile('fotoPerfil')){
                if($usuario->fotoPerfil){
                    Storage::disk('public')->delete($usuario->fotoPerfil);
                }

                $path = $request->file('fotoPerfil')->store('imgsFotoPerfil', 'public');
                $usuario->fotoPerfil = $path;
            }

            $usuario->save();

            return response()->json([
                'success' => true,
                'message' => 'Usuário atualizado com sucesso!',
                'usuario' => $usuario
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao atualizar usuário.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Usuario $usuario)
    {
        if($usuario->fotoPerfil){
            Storage::disk('public')->delete($usuario->fotoPerfil);
        }

        $usuario->delete();
        return response()->json(null, 204);
    }
}
