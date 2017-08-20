<?php

use Illuminate\Database\Seeder;
use Koodilab\Models\Setting;

class SettingsTableSeeder extends Seeder
{
    /**
     * Run the database seed.
     */
    public function run()
    {
        Setting::create([
            'key' => 'title',
            'value' => [
                'en' => 'Galaxy of Drones Online',
            ],
        ]);

        Setting::create([
            'key' => 'description',
            'value' => [
                'en' => 'A multiplayer space strategy game based on Laravel.',
            ],
        ]);

        Setting::create([
            'key' => 'author',
            'value' => [
                'en' => 'Koodilab',
            ],
        ]);
    }
}
