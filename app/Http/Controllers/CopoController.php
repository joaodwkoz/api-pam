<?php

namespace App\Http\Controllers;

use App\Models\Copo;
use Illuminate\Http\Request;

class CopoController extends Controller
{
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
        $copo = Copo::create($request->all());
        return response()->json($copo, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Copo $copo)
    {
        return response()->json($copo);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Copo $copo)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Copo $copo)
    {
        $copo->update($request->all());
        return response()->json($copo);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Copo $copo)
    {
        $copo->delete();
        return response()->json(null, 204);
    }
}
