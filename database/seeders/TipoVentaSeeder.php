<?php

namespace Database\Seeders;

use App\Models\TipoVenta;
use Illuminate\Database\Seeder;

class TipoVentaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $TipoVentas = [
            ['nombre' => 'Venta'],
            ['nombre' => 'Apartado'],
            ['nombre' => 'Pedido']
        ];

        foreach ($TipoVentas as $tipoVenta) {
            TipoVenta::create($tipoVenta);
        }
    }
}
