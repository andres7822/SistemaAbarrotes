<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::create([
            'name' => 'Desarrolador de sistema',
            'username' => 'developer',
            'email' => 'developer@gmail.com',
            'password' => bcrypt('12')
        ]);

        $user->assignRole('1');
    }
}
