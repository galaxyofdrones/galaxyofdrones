<?php

use Illuminate\Database\Seeder;
use Koodilab\Models\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seed.
     */
    public function run()
    {
        User::create([
            'username' => 'koodilab',
            'email' => 'support@koodilab.com',
            'password' => 'havefun',
        ]);
    }
}
