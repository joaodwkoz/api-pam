<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


class ImcController extends Controller
{
    public function index(Request $request)
{
    $historico = $request->user()->imcRegistros()->orderBy('created_at', 'desc')->get();

    return response()->json($historico);

}

    public function store(Request $request)

{
    $dadosValidados = $request->validate([
        'peso'   => 'required|numeric|min:1',
        'altura' => 'required|numeric|min:0.5',
    ]);

    $altura = $dadosValidados['altura'];
    $peso = $dadosValidados['peso'];

    
    if ($altura <= 0) {
        return response()->json(['message' => 'A altura deve ser maior que zero.'], 422);
}

$imcCalculado = $peso / ($altura * $altura);

$registro = $request->user()->imcRegistros()->create([
    'peso'   => $peso,
    'altura' => $altura,
    'imc'    => $imcCalculado,
]);

return response()->json($registro, 201);
}
}
