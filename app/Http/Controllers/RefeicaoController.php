<?php

namespace App\Http\Controllers;

use App\Models\Refeicao;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RefeicaoController extends Controller
{
    public function getRefeicoesByDate(Request $request, $date)
    {
        $refeicoes = $request->user()->refeicoes()->where('data_refeicao', $date)->orderBy('created_at')->get()->groupBy('tipo_refeicao');
        return response()->json($refeicoes);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
    public function storeAPI(Request $request)
    {
        $refeicao = Refeicao::create([
            'usuario_id' => $request->user()->id,
            'nome_alimento' => $request->nome_alimento,
            'calorias' => $request->calorias,
            'quantidade' => $request->quantidade,
            'total_calorias' => $request->calorias * $request->quantidade,
            'tipo_refeicao' => $request->tipo_refeicao,
            'data_refeicao' => $request->data_refeicao,
        ]);

        return response()->json($refeicao, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Refeicao $refeicao)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Refeicao $refeicao)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Refeicao $refeicao)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroyAPI(Refeicao $refeicao)
    {
        $refeicao->delete();
        return response()->json(null, 204);
    }
}
