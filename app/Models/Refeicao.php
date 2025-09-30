<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Refeicao extends Model
{
    use HasFactory;

    protected $table = 'refeicoes';

    protected $fillable = [
        'usuario_id',
        'nome_alimento',
        'calorias',
        'quantidade',
        'total_calorias',
        'tipo_refeicao',
        'data_refeicao',
    ];
}