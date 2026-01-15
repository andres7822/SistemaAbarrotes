<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        $this->call(TiendaSeeder::class);
        $this->call(TipoMenuSeeder::class);
        $this->call(IconoSeeder::class);
        $this->call(TypePermissionSeeder::class);
        $this->call(MenuSeeder::class);
        $this->call(PermissionSeeder::class);
        $this->call(RoleSeeder::class);
        $this->call(UserSeeder::class);
        $this->call(SexoSeeder::class);
        $this->call(MeseSeeder::class);
        $this->call(TipoVentaSeeder::class);
        $this->call(AccioneSeeder::class);
        $this->call(EstatusVentaSeeder::class);
        $this->call(TipoIngresoSeeder::class);
        $this->call(TipoMovimientoSeeder::class);
    }
}
