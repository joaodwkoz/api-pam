<?php

namespace App\Http\Controllers;

use App\Models\Batimento;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class BatimentoController extends Controller
{
   
    public function index(): JsonResponse
    {
        $batimentos = Batimento::all(); 

        return response()->json($batimentos);
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
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'bpm' => 'required|integer|min:30|max:250', 
            'condicao' => 'required|in:repouso,pos_exercicio,monitoramento,aleatorio',
            'data_hora_medicao' => 'required|date',
            'observacoes' => 'nullable|string',
            
        ]);

        $data = $request->all();

        $batimento = Batimento::create($data);

        return response()->json($batimento, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Batimento $batimento): JsonResponse
    {
       return response()->json($batimento);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Batimento $batimento)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Batimento $batimento): JsonResponse
    {
        $request->validate([
            'bpm' => 'sometimes|required|integer|min:30|max:250',
            'condicao' => 'sometimes|required|in:repouso,pos_exercicio,monitoramento,aleatorio',
            'data_hora_medicao' => 'sometimes|required|date',
            'observacoes' => 'nullable|string',
        ]);

        $batimento->update($request->all());

        return response()->json($batimento);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Batimento $batimento): JsonResponse
    {
        $batimento->delete();

        return response()->json(null, 204);
    }
}
