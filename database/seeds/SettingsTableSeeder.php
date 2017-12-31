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
                'en' => 'An open source multiplayer space strategy game.',
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
