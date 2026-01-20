<?php

namespace Database\Seeders;

use App\Models\TipoDescuento;
use Illuminate\Database\Seeder;

class TipoDescuentoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $TipoDescuentos = [
            //Ej: Producto con 50% de descuento
            ['nombre' => 'Porcentaje'],
            //Ej: Producto con $100 de descuento
            ['nombre' => 'Cantidad Fija'],
            //Ej: Lleva 3 y paga 2
            ['nombre' => 'Pieza'],
            //Ej: A la cantidad de 10 piezas se da el 20% de descuento
            ['nombre' => 'Mayoreo'],
            //Ej: En la compra de 3 llevate el 4 a mitad de precio
            ['nombre' => 'Pieza y Porcentaje']
        ];
        foreach ($TipoDescuentos as $TipoDescuento) {
            TipoDescuento::create($TipoDescuento);
        }
    }
}
