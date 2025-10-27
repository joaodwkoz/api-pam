<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Consumo;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class ConsumoController extends Controller
{
    public function getChartData(Request $request, Usuario $usuario)
    {
        $periodo = $request->input('periodo', 'semana');

        $endDate = Carbon::now()->endOfDay();

        $startDate = match($periodo) {
            'hoje' => Carbon::now()->subHours(24),
            'mes' => Carbon::now()->subDays(30)->endOfDay(),
            'ano' => Carbon::now()->subDays(365)->endOfDay(),
            default => Carbon::now()->subDays(7)->endOfDay(),
        };

        $groupingFunction = match($periodo) {
            'ano' => DB::raw('DATE_FORMAT(consumido_em, "%Y-%m") as data_registro'),
            'hoje' => DB::raw('DATE_FORMAT(consumido_em, "%Y-%m-%d %H:00") as data_registro'), 
            default => DB::raw('DATE(consumido_em) as data_registro'),
        };
            
        $keyFormat = match($periodo) {
            'ano' => 'Y-m',
            'hoje' => 'Y-m-d H:00',
            default => 'Y-m-d',
        };

        $aggregatedData = $usuario->consumos()
            ->select(
                $groupingFunction, 
                DB::raw('SUM(volume_ml) as volume_total') 
            )
            ->whereBetween('consumido_em', [$startDate, $endDate])
            ->groupBy('data_registro')
            ->orderBy('data_registro')
            ->get();
            
        $dataSeries = $this->formatChartData($aggregatedData, $startDate, $endDate, $periodo, $keyFormat);

        return response()->json([
            'meta_total' => $usuario->meta_diaria * count($dataSeries),
            'dados_grafico' => $dataSeries,
        ], 200);
    }

    public function getDetailedHistory(Request $request, Usuario $usuario)
    {
        $periodo = $request->input('periodo', 'semana');

        $endDate = Carbon::now()->endOfDay();

        $startDate = match($periodo) {
            'hoje' => Carbon::now()->subHours(24),
            'mes' => Carbon::now()->subDays(30)->endOfDay(),
            'ano' => Carbon::now()->subDays(365)->endOfDay(),
            default => Carbon::now()->subDays(7)->endOfDay(),
        };

        $consumos = $usuario->consumos()
            ->whereBetween('consumido_em', [$startDate, $endDate])
            ->with(['copo.icone'])
            ->orderBy('consumido_em', 'desc')
            ->get();

        $groupedHistory = $consumos->groupBy(function ($item) {
            return Carbon::parse($item->consumido_em)->locale('pt_BR')->isoFormat('D [de] MMMM, dddd');
        })->map(function ($dayConsumos, $dateLabel) {
            return [
                'title' => $dateLabel,
                'data' => $dayConsumos->map(function ($consumo) {
                    return [
                        'id' => $consumo->id,
                        'volume_ml' => $consumo->volume_ml,
                        'hora' => Carbon::parse($consumo->consumido_em)->format('H:i'),
                        'copo_nome' => $consumo->copo->nome ?? 'Manual',
                        'copo_icone_caminho' => $consumo->copo->icone->caminhoFoto ?? null,
                    ];
                })->all(),
                'total_diario' => (int) $dayConsumos->sum('volume_ml'),
            ];
        })->values()->all();

        return response()->json([
            'historico' => $groupedHistory,
        ], 200);
    }

    public function showByUser(Request $request, Usuario $usuario)
    {
        return response()->json($usuario->consumos, 200);
    }

    public function showByUserByDate(Request $request, Usuario $usuario)
    {
        $dataQuery = $request->query('data', Carbon::today()->toDateString());

        $data = Carbon::parse($dataQuery); 
        
        $comeco = $data->copy()->startOfDay();
        $fim = $data->copy()->endOfDay();
        
        $consumos = $usuario->consumos()
            ->whereBetween('consumido_em', [$comeco, $fim])
            ->with(['copo.icone']) 
            ->orderBy('consumido_em', 'asc') 
            ->get();

        $totalVolume = $consumos->sum('volume_ml');

        return response()->json([
            'total_volume_ml' => (int) $totalVolume,
            'registros' => $consumos, 
        ], 200);
    }

    public function index()
    {
        return response()->json(Consumo::all(), 200);
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
        $consumo = Consumo::create($request->all());
        return response()->json($consumo, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Consumo $consumo)
    {
        return response()->json($consumo);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Consumo $consumo)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Consumo $consumo)
    {
        $consumo->update($request->all());
        return response()->json($consumo);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Consumo $consumo)
    {
        $consumo->delete();
        return response()->json(null, 204);
    }

    private function formatChartData($aggregatedData, $startDate, $endDate, $periodo, $keyFormat)
    {
        $dataMap = $aggregatedData->keyBy('data_registro')->map(function ($item) {
            return (int) $item->volume_total;
        })->all();

        $series = [];
        $currentDate = $startDate->copy();
        
        $interval = match($periodo) {
            'hoje' => 'hour',
            'ano' => 'month',
            default => 'day',
        };
        
        while ($currentDate->lessThanOrEqualTo($endDate)) {
            $dateKey = $currentDate->format($keyFormat); 
            
            $label = match($periodo) {
                'hoje' => $currentDate->locale('pt_BR')->isoFormat('HH[h]'),
                'mes' => $currentDate->locale('pt_BR')->isoFormat('D/MM'),
                'ano' => $currentDate->locale('pt_BR')->isoFormat('MMM'),
                'semana' => $currentDate->locale('pt_BR')->isoFormat('ddd'),
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