<?php

namespace Database\Seeders;

use App\Models\Accione;
use Illuminate\Database\Seeder;

class AccioneSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $acciones = [
            ['nombre' => 'Inicio Sesión'],
            ['nombre' => 'Cerrò Sesión'],
            ['nombre' => 'Ver'],
            ['nombre' => 'Crear'],
            ['nombre' => 'Editar'],
            ['nombre' => 'Eliminar'],
        ];

        foreach ($acciones as $accione) {
            Accione::create($accione);
        }
    }
}
