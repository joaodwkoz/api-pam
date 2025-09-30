<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Subscriber\Oauth\Oauth1;

class AlimentoController extends Controller
{
    public function search(Request $request)
    {
        $query = $request->input('query');
        
        $client = new Client(['base_uri' => 'https://platform.fatsecret.com/rest/server.api']);
        $stack = HandlerStack::create();

        $middleware = new Oauth1([
            'consumer_key'    => config('app.fatsecret_key'),
            'consumer_secret' => config('app.fatsecret_secret'),
            'token'           => '',
            'token_secret'    => ''
        ]);

        try {
            $res = $client->get('', [
                'handler' => $stack,
                'auth' => 'oauth',
                'query' => [
                    'method' => 'foods.search',
                    'search_expression' => $query,
                    'format' => 'json',
                    'max_results' => 15,
                ]
            ]);

            $data = json_decode($res->getBody()->getContents(), true);

            $formattedResults = collect($data['foods']['food'] ?? [])->map(function ($food) {
                return [
                    'id' => $food['food_id'],
                    'name' => $food['food_name'],
                    'type' => $food['food_type'],
                    'description' => $food['food_description'] ?? '',
                ];
            });

            return response()->json($formattedResults);
        } catch(\Exception $e) {
            return response()->json(['error' => 'Erro ao conectar à API do FatSecret'], 500);
        }

        $stack->push($middleware);
    }
}
