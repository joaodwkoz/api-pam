<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reacao extends Model
{
    use HasFactory;

    protected $table = 'reacoes';

    protected $fillable =[
        'nome',
    ];

    public function alergias()
    {
        return $this->belongsToMany(Alergia::class);
    }
}
