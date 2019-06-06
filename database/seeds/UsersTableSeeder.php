<?php

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
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
            'email_verified_at' => Carbon::now(),
            'password' => Hash::make('havefun'),
        ]);
    }
}
