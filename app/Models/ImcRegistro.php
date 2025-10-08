<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ImcRegistro extends Model
{
    use HasFactory;

    protected $fillable =[
        'usuario_id',
        'peso',
        'altura',
        'imc',
    ];

    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }
}
