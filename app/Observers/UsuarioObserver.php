<?php

namespace App\Observers;

use App\Models\Usuario;

class UsuarioObserver
{
    /**
     * Handle the Usuario "created" event.
     */
    public function created(Usuario $usuario): void
    {
        $coposPadrao = [
            [
                'nome' => 'Copo',
                'capacidade_ml' => 300,
                'icone_id' => 2,
            ],
            [
                'nome' => 'Garrafa esportiva',
                'capacidade_ml' => 500,
                'icone_id' => 4,
            ],
            [
                'nome' => 'Garrafão',
                'capacidade_ml' => 1000,
                'icone_id' => 5,
            ],
            [
                'nome' => 'Galão',
                'capacidade_ml' => 2000,
                'icone_id' => 7,
            ]
        ];

        foreach ($coposPadrao as $copo) {
            $usuario->copos()->create($copo);
        }
    }

    /**
     * Handle the Usuario "updated" event.
     */
    public function updated(Usuario $usuario): void
    {
        //
    }

    /**
     * Handle the Usuario "deleted" event.
     */
    public function deleted(Usuario $usuario): void
    {
        //
    }

    /**
     * Handle the Usuario "restored" event.
     */
    public function restored(Usuario $usuario): void
    {
        //
    }

    /**
     * Handle the Usuario "force deleted" event.
     */
    public function forceDeleted(Usuario $usuario): void
    {
        //
    }
}
