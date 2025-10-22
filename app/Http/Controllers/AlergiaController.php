<?php

namespace App\Http\Controllers;

use App\Models\Alergia;
use App\Models\Reacao;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AlergiaController extends Controller
{
    public function index(Request $request)
    {
      
        $alergias = $request->user()->alergias;
        return response()->json($alergias);
    }

    public function showByUser(Request $request, Usuario $usuario)
    {
        $alergias = $usuario->alergias;
        return response()->json($alergias->load('reacoes'), 200);
    }

    public function store(Request $request)
    {
        $alergia = DB::transaction(function () use ($request) {
            
            $alergia = $request->user()->alergias()->create([
                'nome' => $request->nome,
                'categoria' => $request->categoria,
                'gravidade' => $request->gravidade,
                'descricao' => $request->descricao,
            ]);

            $reacaoIds = [];
            if ($request->has('reacoes') && is_array($request->reacoes)) {
                foreach($request->reacoes as $nomeReacao) {
                    $reacao = Reacao::firstOrCreate(['nome' => $nomeReacao]);
                    $reacaoIds[] = $reacao->id;
                }
            }

            if (!empty($reacaoIds)) {
                $alergia->reacoes()->sync($reacaoIds);
            }

            return $alergia;
        });

        return response()->json($alergia->load('reacoes'), 201); 
    }

    public function update(Request $request, Alergia $alergia)
    {
        DB::transaction(function () use ($request, $alergia) {
            $alergia->update([
                'nome' => $request->nome,
                'categoria' => $request->categoria,
                'gravidade' => $request->gravidade,
                'descricao' => $request->descricao,
            ]);

            $reacaoIds = [];
            if ($request->has('reacoes') && is_array($request->reacoes)) {
                foreach($request->reacoes as $nomeReacao) {
                    $reacao = Reacao::firstOrCreate(['nome' => $nomeReacao]);
                    $reacaoIds[] = $reacao->id;
                }
            }

            $alergia->reacoes()->sync($reacaoIds);
        });

        return response()->json($alergia->load('reacoes'), 200); 
    }

    public function destroy(Alergia $alergia)
    {
        $alergia->delete();

        return response()->json(['message' => 'Alergia removida com sucesso'], 200);
    }
}



