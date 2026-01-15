<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class actionPermissionController extends Controller
{
    protected $vistaName, $register;

    public function __construct(string $vistaName)
    {
        $this->middleware(["auth", "superadmin"]); // superadmin primero

        // Luego vienen los permisos
        $this->middleware("permission:ver-$vistaName|crear-$vistaName|editar-$vistaName|eliminar-$vistaName", ["only" => ["index", "show"]]);
        $this->middleware("permission:crear-$vistaName", ["only" => ["create", "store"]]);
        $this->middleware("permission:editar-$vistaName", ["only" => ["edit", "update"]]);
        $this->middleware("permission:eliminar-$vistaName", ["only" => ["destroy"]]);

        $this->register = new registroAccioneController();
    }


}
