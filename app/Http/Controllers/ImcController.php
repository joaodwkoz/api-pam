<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Consumo;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class ImcController extends Controller
{
    public function getLatestRegistry(Request $request, Usuario $usuario)
    {
        $usuario = $request->user();
        return $usuario->imcRegistros()->latest()->first();
    }

    public function getDetailedHistory(Request $request, Usuario $usuario)
    {
        $registros = $usuario->imcRegistros()
            ->orderBy('created_at', 'desc')
            ->get();

        $groupedHistory = $registros->groupBy(function ($item) {
            return Carbon::parse($item->created_at)->locale('pt_BR')->tz('America/Sao_Paulo')->isoFormat('D [de] MMMM, dddd');
        })->map(function ($dayRegistros, $dateLabel) {
            return [
                'title' => $dateLabel,
                'data' => $dayRegistros->map(function ($imcRegistro) {
                    return [
                        'id' => $imcRegistro->id,
                        'peso' => $imcRegistro->peso,
                        'altura' => $imcRegistro->altura,
                        'imc' => $imcRegistro->imc,
                        'hora' => Carbon::parse($imcRegistro->created_at)->tz('America/Sao_Paulo')->format('H:i'),
                    ];
                })->all(),
                'media_imc' => (int) $dayRegistros->avg('bpm'),
            ];
        })->values()->all();

        return response()->json([
            'historico' => $groupedHistory,
        ], 200);
    }

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