<?php

namespace Database\Seeders;

use App\Models\Icone;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\File;

class IconesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Storage::disk('public')->deleteDirectory('icones');

        $icones = [
            [
                'nome' => "Xicara",
                'caminhoFoto' => 'assets/xicara.png'
            ],
            [
                'nome' => "Copo",
                'caminhoFoto' => 'assets/copo.png'
            ],
            [
                'nome' => "Garrafa",
                'caminhoFoto' => 'assets/garrafa.png'
            ],
            [
                'nome' => "Garrafa esportiva",
                'caminhoFoto' => 'assets/garrafaesportiva.png'
            ],
            [
                'nome' => "Garrafão",
                'caminhoFoto' => 'assets/garrafao.png'
            ],
            [
                'nome' => "Jarra",
                'caminhoFoto' => 'assets/jarra.png'
            ],
            [
                'nome' => "Galão",
                'caminhoFoto' => 'assets/galao.png'
            ],
        ];

        foreach ($icones as $icone) {
            Icone::create([
                'nome' => $icone['nome'],
                'caminhoFoto' => $icone['caminhoFoto'],
            ]);
        }
    }
}
