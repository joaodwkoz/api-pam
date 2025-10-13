<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Copo extends Model
{
    use HasFactory;

    protected $table = 'copos';
    protected $fillable = ['nome', 'capacidade_ml', 'icone_id', 'usuario_id'];

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'usuario_id');
    }

    public function icone()
    {
        return $this->belongsTo(Icone::class, 'icone_id');
    }

    public function consumos()
    {
        return $this->hasMany(Consumo::class, 'copo_id');
    }
}