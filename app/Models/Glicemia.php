<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Glicemia extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'valor',
        'tipo_medicao',
        'data_hora_medicao',
        'observacoes',
    ];

    public function usuario(): BelongsTo
{
    return $this->belongsTo(User::class);
    }
}
