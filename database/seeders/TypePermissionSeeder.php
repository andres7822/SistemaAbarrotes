<?php

namespace Database\Seeders;

use App\Models\TypePermission;
use Illuminate\Database\Seeder;

class TypePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $TypePermissions = [
            'ver',
            'crear',
            'editar',
            'eliminar'
        ];

        foreach ($TypePermissions as $permission) {
            TypePermission::create(['name' => $permission]);
        }

    }
}
