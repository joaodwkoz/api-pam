<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Batimento extends Model
{
    use HasFactory;

    protected $fillable = [
        'usuario_id',
        'bpm',
        'condicao',
        'data_hora_medicao',
        'observacoes',
    ];

    public function usuario()
    {
        return $this->belongsTo(Usuario::class);
    }

}
