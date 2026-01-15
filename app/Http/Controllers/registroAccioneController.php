<?php

namespace App\Http\Controllers;

use App\Models\RegistroAccione;
use Illuminate\Http\Request;

class registroAccioneController extends Controller
{
    /**
     * @param $origen
     * @param $tupla
     * @param $accion
     * 1 = Inicio Sesión,
     * 2 = Cerró Sesión,
     * 3 = Ver,
     * 4 = Crear,
     * 5 = Editar,
     * 6 = Eliminar
     * @param $detalle
     * @return void
     */
    public function registro($origen, $tupla, $accion, $detalle)
    {
        $user_id = auth()->user()->id;

        if (is_array($detalle)) {
            $detalle = json_encode($detalle);
        }

        RegistroAccione::create([
            'origen' => $origen,
            'tupla' => $tupla,
            'detalle' => $detalle,
            'accione_id' => $accion,
            'user_id' => $user_id
        ]);
    }
}
