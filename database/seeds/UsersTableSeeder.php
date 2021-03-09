<?php

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

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
