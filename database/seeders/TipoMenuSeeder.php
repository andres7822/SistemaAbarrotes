<?php

namespace Database\Seeders;

use App\Models\TipoMenu;
use Illuminate\Database\Seeder;

class TipoMenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $TipoMenus = [
            'Vista',
            'Menú',
            'Subvista',
            'Submenú'
        ];

        foreach ($TipoMenus as $tipoMenu) {
            TipoMenu::create(['Nombre' => $tipoMenu]);
        }
    }
}
