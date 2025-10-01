<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use GuzzleHttp\Client;

class AlimentoController extends Controller
{
    public function search(Request $request)
    {
        $query = $request->input('query');

        $client = new Client(['base_uri' => 'https://br.openfoodfacts.org/']);

        try {
            $res = $client->get('cgi/search.pl', [
                'query' => [
                    'search_terms' => $query,
                    'search_simple' => 1,
                    'action' => 'process',
                    'json' => 1,
                    'page_size' => 20
                ]
            ]);

            $data = json_decode($res->getBody()->getContents(), true);

            $formattedResults = collect($data['products'] ?? [])
                ->filter(function ($product) {
                    return !empty($product['product_name_pt']) && isset($product['nutriments']['energy-kcal_100g']);
                })
                ->map(function ($product) {
                    return [
                        'id' => $product['_id'],
                        'name' => $product['product_name_pt'] ?? $product['product_name'],
                        'brand' => $product['brands'] ?? 'Marca desconhecida',
                        'image_url' => $product['image_front_url'] ?? '',
                        'calories_per_100g' => $product['nutriments']['energy-kcal_100g'],
                    ];
                })->values();

            return response()->json($formattedResults);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Erro ao conectar à API da Open Food Facts',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}