<?php

namespace App\Http\Controllers;

use App\Models\Alergia;
use Illuminate\Http\Request;

class AlergiaController extends Controller
{
    public function index(Request $request)
    {
      
        $alergias = $request->user()->alergias;
        return response()->json($alergias);
    }

    public function store(Request $request)

    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'descricao' => 'nullable|string',
        ]);

        $alergia = $request->user()->alergias()->create([
            'nome' => $request->nome,
            'descricao' => $request->descricao,
        ]);

        return response()->json($alergia, 201); 
    }

    public function destroy(Alergia $alergia)
    {
        $alergia->delete();

        return response()->json(['message' => 'Alergia removida com sucesso'], 200);
    }
}



