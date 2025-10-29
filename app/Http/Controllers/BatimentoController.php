<?php

namespace App\Http\Controllers;

use App\Models\Batimento;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Models\Usuario;
use Illuminate\Support\Facades\DB;

class BatimentoController extends Controller
{
    public function getChartData(Request $request, Usuario $usuario)
    {
        $periodo = $request->input('periodo', '7d');

        $endDate = Carbon::now()->endOfDay();

        $startDate = match($periodo) {
            '24h'  => Carbon::now()->subHours(24),
            '30d'  => Carbon::now()->subDays(30)->startOfDay(),
            '365d' => Carbon::now()->subDays(365)->startOfDay(),
            default => Carbon::now()->subDays(7)->startOfDay(),
        };

        $groupingFunction = match($periodo) {
            '24h'  => DB::raw('DATE_FORMAT(data_hora_medicao, "%Y-%m-%d %H:00") as data_registro'),
            '365d' => DB::raw('DATE_FORMAT(data_hora_medicao, "%Y-%m") as data_registro'),
            default => DB::raw('DATE(data_hora_medicao) as data_registro'),
        };
            
        $keyFormat = match($periodo) {
            '24h'  => 'Y-m-d H:00',
            '365d' => 'Y-m',
            default => 'Y-m-d',
        };

        $baseQuery = $usuario->batimentos()
            ->whereBetween('data_hora_medicao', [$startDate, $endDate]);

        $aggregatedData = $baseQuery
            ->select(
                $groupingFunction, 
                DB::raw('AVG(bpm) as valor_medio') 
            )
            ->groupBy('data_registro')
            ->orderBy('data_registro')
            ->get();
            
        $dataSeries = $this->formatChartData(
            $aggregatedData, 
            $startDate, 
            $endDate, 
            $periodo, 
            $keyFormat
        );

        return response()->json([
            'dados_grafico' => $dataSeries,
        ], 200);
    }

    public function showByUser(Request $request, Usuario $usuario)
    {
        $media = $usuario->batimentos()->avg('bpm');
        $repouso = $usuario->batimentos()->where('condicao', 'repouso')->avg('bpm');
        $maximo = $usuario->batimentos()->max('bpm');
        $minimo = $usuario->batimentos()->min('bpm');

        return response()->json([
            'media' => $media,
            'media_repouso' => $repouso,
            'maximo' => $maximo,
            'minimo' => $minimo,
        ], 200);
    }

    public function getDetailedHistory(Request $request, Usuario $usuario)
    {
        $periodo = $request->input('periodo', '7d');

        $endDate = Carbon::now()->endOfDay();

        $startDate = match($periodo) {
            '24h' => Carbon::now()->subHours(24),
            '30d' => Carbon::now()->subDays(30)->endOfDay(),
            '365d' => Carbon::now()->subDays(365)->endOfDay(),
            default => Carbon::now()->subDays(7)->endOfDay(),
        };

        $batimentos = $usuario->batimentos()
            ->whereBetween('data_hora_medicao', [$startDate, $endDate])
            ->orderBy('data_hora_medicao', 'desc')
            ->get();

        $groupedHistory = $batimentos->groupBy(function ($item) {
            return Carbon::parse($item->data_hora_medicao)->tz('America/Sao_Paulo')->locale('pt_BR')->isoFormat('D [de] MMMM, dddd');
        })->map(function ($dayBatimentos, $dateLabel) {
            return [
                'title' => $dateLabel,
                'data' => $dayBatimentos->map(function ($batimento) {
                    return [
                        'id' => $batimento->id,
                        'bpm' => $batimento->bpm,
                        'condicao' => $batimento->condicao,
                        'hora' => Carbon::parse($batimento->data_hora_medicao)->tz('America/Sao_Paulo')->format('H:i'),
                        'data' => Carbon::parse($batimento->data_hora_medicao)->tz('America/Sao_Paulo')->format('d-m-Y'),
                        'observacoes' => $batimento->observacoes
                    ];
                })->all(),
                'media_bpm' => (int) $dayBatimentos->avg('bpm'),
            ];
        })->values()->all();

        return response()->json([
            'historico' => $groupedHistory,
        ], 200);
    }
   
    public function index()
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
    public function store(Request $request)
    {
        $request->validate([
            'bpm' => 'required|integer|min:30|max:250', 
            'condicao' => 'required|in:repouso,pos_exercicio,monitoramento,aleatorio',
            'data_hora_medicao' => 'required|date',
            'observacoes' => 'nullable|string',
        ]);

        $data = $request->all();

        $request->user()->batimentos()->create($data);

        return response()->json($data, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Batimento $batimento)
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
    public function update(Request $request, Batimento $batimento)
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
    public function destroy(Batimento $batimento)
    {
        $batimento->delete();

        return response()->json(null, 204);
    }

    private function formatChartData($aggregatedData, $startDate, $endDate, $periodo, $keyFormat)
    {
        $dataMap = $aggregatedData->keyBy('data_registro')->map(function ($item) {
            return (float) round($item->valor_medio, 1);
        })->all();

        $series = [];
        $currentDate = $startDate->copy();
        
        $interval = match($periodo) {
            '24h'  => 'hour',
            '365d' => 'month',
            default => 'day',
        };
        
        while ($currentDate->lessThanOrEqualTo($endDate)) {
            $dateKey = $currentDate->format($keyFormat); 
            
            $label = match($periodo) {
                '24h'  => $currentDate->locale('pt_BR')->isoFormat('HH[h]'),
                '365d' => $currentDate->locale('pt_BR')->isoFormat('MMM'),
                '7d'   => $currentDate->locale('pt_BR')->isoFormat('ddd'),
                '30d'  => $currentDate->locale('pt_BR')->isoFormat('D/MM'),
                default => $currentDate->locale('pt_BR')->isoFormat('D/MM'),
            };
            
            $series[] = [
                'label' => $label,
                'value' => $dataMap[$dateKey] ?? 0,
            ];

            $currentDate->add($interval, 1);
        }

        return $series;
    }
}