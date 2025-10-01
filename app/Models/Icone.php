<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Icone extends Model
{
    use HasFactory;

    protected $table = 'icones';
    protected $fillable = ['nome', 'fotoIcone', 'tipo'];

    public function copos()
    {
        return $this->hasMany(Copo::class, 'icone_id');
    }
}
