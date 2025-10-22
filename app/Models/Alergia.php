<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Alergia extends Model
{
    use HasFactory;

    protected $fillable =[
        'usuario_id',
        'nome',
        'categoria',
        'gravidade',
        'descricao',
    ];

    public function usuario()
    {
        return $this->belongsTo(Usuario::class);
    }

    public function reacoes()
    {
        return $this->belongsToMany(Reacao::class);
    }
}
