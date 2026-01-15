<?php

namespace Database\Seeders;

use App\Models\Sexo;
use Illuminate\Database\Seeder;

class SexoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $Sexos = [
            ['nombre' => 'Masculino'],
            ['nombre' => 'Femenino'],
        ];

        foreach ($Sexos as $Sexo) {
            Sexo::create($Sexo);
        }
    }
}
