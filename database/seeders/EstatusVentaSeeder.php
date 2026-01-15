<?php

namespace Database\Seeders;

use App\Models\EstatusVenta;
use Illuminate\Database\Seeder;

class EstatusVentaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $estatusVentas = [
            ['nombre' => 'CREANDO', 'bgClass' => 'fw-bolder rounded p-1 bg-warning text-white'],
            ['nombre' => 'EDITANDO', 'bgClass' => 'fw-bolder rounded p-1 bg-warning text-white'],
            ['nombre' => 'TERMINADO', 'bgClass' => 'fw-bolder rounded p-1 bg-success text-white'],
            ['nombre' => 'CANCELADO', 'bgClass' => 'fw-bolder rounded p-1 bg-danger text-white'],
        ];

        foreach ($estatusVentas as $estatusVenta) {
            EstatusVenta::create($estatusVenta);
        }
    }
}
