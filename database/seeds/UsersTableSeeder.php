<?php

use Illuminate\Database\Seeder;
use App\User;
use App\Admin;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       User::create([
            'name' => 'user',
            'email' => 'karimunenam@gmail.com',
            'password' => bcrypt('123456'),
            'active'=>false,
            'activation_token'=>str_random(255)
        ]);

       Admin::create([
            'name' => 'admin',
            'email' => 'ekoabdulaziz96@gmail.com',
            'password' => bcrypt('123456'),
            'active'=>false,
            'activation_token'=>str_random(255)
        ]);
    }
}
