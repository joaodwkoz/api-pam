<?php

namespace App\Http\Controllers;

use App\Models\Glicemia;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class GlicemiaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $glicemias = Glicemia::all(); 

        return response()->json($glicemias);
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
            'valor' => 'required|integer|min:20|max:600', 
            'tipo_medicao' => 'required|in:jejum,pre_refeicao,pos_refeicao,aleatoria',
            'data_hora_medicao' => 'required|date',
            'observacoes' => 'nullable|string',
            
        ]);

        $data = $request->all();
        
        $glicemia = Glicemia::create($data);

        return response()->json($glicemia, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Glicemia $glicemia): JsonResponse
    {
        return response()->json($glicemia);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Glicemia $glicemia)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Glicemia $glicemia): JsonResponse
    {
        $request->validate([
            'valor' => 'sometimes|required|integer|min:20|max:600',
            'tipo_medicao' => 'sometimes|required|in:jejum,pre_refeicao,pos_refeicao,aleatoria',
            'data_hora_medicao' => 'sometimes|required|date',
            'observacoes' => 'nullable|string',
        ]);

        $glicemia->update($request->all());

        return response()->json($glicemia);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Glicemia $glicemia): JsonResponse
    {
        $glicemia->delete();

        return response()->json(null,204);
    }
}
