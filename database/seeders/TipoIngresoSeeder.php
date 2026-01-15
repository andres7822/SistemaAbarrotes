<?php

namespace Database\Seeders;

use App\Models\TipoIngreso;
use Illuminate\Database\Seeder;

class TipoIngresoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $tipoIngresos = [
            ['nombre' => 'Efectivo'],
            ['nombre' => 'Tarjeta Débito'],
            ['nombre' => 'Tarjeta Crédito'],
            ['nombre' => 'Transferencia'],
            ['nombre' => 'Depósito'],
        ];

        foreach ($tipoIngresos as $tipoIngreso) {
            TipoIngreso::create($tipoIngreso);
        }
    }
}
