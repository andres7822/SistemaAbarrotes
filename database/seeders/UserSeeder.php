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

        $users = [
            [
                'name' => 'Desarrolador de sistema',
                'username' => 'developer',
                'email' => 'developer@gmail.com',
                'password' => bcrypt('12'),
                'tienda_id' => 1
            ],
            [
                'name' => 'José Andrés Vergara Arellano',
                'username' => 'admin',
                'email' => 'admin@gmail.com',
                'password' => bcrypt('admin'),
                'tienda_id' => 1
            ],
        ];

        foreach ($users as $index => $item) {
            $user = User::create($item);
            $user->assignRole($index + 1);
        }
        /*$user = User::create([
            'name' => 'Desarrolador de sistema',
            'username' => 'developer',
            'email' => 'developer@gmail.com',
            'password' => bcrypt('12')
        ]);

        $user->assignRole('1');*/
    }
}
