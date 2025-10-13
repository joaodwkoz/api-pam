<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Consumo extends Model
{
    use HasFactory;

    protected $table = 'consumos';
    protected $fillable = ['volume_ml', 'usuario_id', 'copo_id'];

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'usuario_id');
    }

    public function copo()
    {
        return $this->belongsTo(Copo::class, 'copo_id');
    }
}