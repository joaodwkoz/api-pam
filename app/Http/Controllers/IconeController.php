<?php

namespace App\Http\Controllers;

use App\Models\Icone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class IconeController extends Controller
{
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
    public function store(Request $request)
    {
        $icone = Icone::fill($request->except('fotoIcone'));

        if($request->hasFile('fotoIcone')) {
            $file = $request->file('fotoIcone');
            $path = $file->store('icones', 'public');
            $icone->fotoIcone = $path;
        }

        $icone->save();

        return response()->json($icone, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Icone $icone)
    {
        return response()->json($icone);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Icone $icone)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Icone $icone)
    {
        try {
            $icone->fill($request->except(['fotoIcone']));

            if($request->hasFile('fotoIcone')){
                if($icone->fotoIcone){
                    Storage::disk('public')->delete($icone->fotoIcone);
                }

                $path = $request->file('fotoIcone')->store('icones', 'public');
                $icone->fotoIcone = $path;
            }

            $icone->save();

            return response()->json([
                'success' => true,
                'message' => 'Usuário atualizado com sucesso!',
                'icone' => $icone
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
    public function destroy(Icone $icone)
    {
        if($icone->fotoIcone){
            Storage::disk('public')->delete($icone->fotoIcone);
        }

        $icone->delete();
        return response()->json(null, 204);
    }
}
