<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Usuario extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'usuarios';

    protected $fillable = ['name', 'email', 'password', 'fotoPerfil', 'cep', 'numero', 'complemento', 'logradouro', 'bairro', 'cidade', 'estado'];

    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'password' => 'hashed',
    ];

    public function refeicoes()
    {
        return $this->hasMany(Refeicao::class, 'usuario_id');
    }

    public function copos()
    {
        return $this->hasMany(Copo::class, 'usuario_id');
    }
}