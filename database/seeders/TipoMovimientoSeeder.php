<?php

namespace Database\Seeders;

use App\Models\TipoMovimiento;
use Illuminate\Database\Seeder;

class TipoMovimientoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $tipoMovimientos = [
            ['nombre' => 'Ingreso'],
            ['nombre' => 'Egreso'],
        ];

        foreach ($tipoMovimientos as $tipoMovimiento) {
            TipoMovimiento::create($tipoMovimiento);
        }
    }
}
