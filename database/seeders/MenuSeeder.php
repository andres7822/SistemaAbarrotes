<?php

namespace Database\Seeders;

use App\Models\Menu;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $Menus = [
            [
                'nombre' => 'ADMINISTRADOR',
                'prioridad' => 999,
                'icono_id' => 1,
                'tipo_menu_id' => 2,
            ],
            [
                'nombre' => 'Roles',
                'nombre_ruta' => 'role',
                'prioridad' => 10,
                'icono_id' => 524,
                'tipo_menu_id' => 3,
                'menu_id' => 1
            ],
            [
                'nombre' => 'Usuarios',
                'nombre_ruta' => 'user',
                'prioridad' => 20,
                'icono_id' => 393,
                'tipo_menu_id' => 3,
                'menu_id' => 1
            ],
            [
                'nombre' => 'Menus',
                'nombre_ruta' => 'menu',
                'prioridad' => 30,
                'icono_id' => 537,
                'tipo_menu_id' => 3,
                'menu_id' => 1
            ],
        ];

        foreach ($Menus as $menu) {
            Menu::create($menu);
        }

    }
}
