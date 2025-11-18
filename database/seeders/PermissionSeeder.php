<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $Permissions = [
            [
                'name' => 'ver-role',
                'menu_id' => 2,
                'type_permission_id' => 1
            ],
            [
                'name' => 'crear-role',
                'menu_id' => 2,
                'type_permission_id' => 2
            ],
            [
                'name' => 'editar-role',
                'menu_id' => 2,
                'type_permission_id' => 3
            ],
            [
                'name' => 'eliminar-role',
                'menu_id' => 2,
                'type_permission_id' => 4
            ],
            [
                'name' => 'ver-user',
                'menu_id' => 3,
                'type_permission_id' => 1
            ],
            [
                'name' => 'crear-user',
                'menu_id' => 3,
                'type_permission_id' => 2
            ],
            [
                'name' => 'editar-user',
                'menu_id' => 3,
                'type_permission_id' => 3
            ],
            [
                'name' => 'eliminar-user',
                'menu_id' => 3,
                'type_permission_id' => 4
            ],
            [
                'name' => 'ver-menu',
                'menu_id' => 4,
                'type_permission_id' => 1
            ],
            [
                'name' => 'crear-menu',
                'menu_id' => 4,
                'type_permission_id' => 2
            ],
            [
                'name' => 'editar-menu',
                'menu_id' => 4,
                'type_permission_id' => 3
            ],
            [
                'name' => 'eliminar-menu',
                'menu_id' => 4,
                'type_permission_id' => 4
            ],

        ];

        foreach ($Permissions as $permission) {
            Permission::create($permission);
        }
    }
}
