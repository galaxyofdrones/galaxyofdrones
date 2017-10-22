<?php

use Illuminate\Database\Seeder;
use Koodilab\Models\Resource;

class ResourcesTableSeeder extends Seeder
{
    /**
     * {@inheritdoc}
     */
    public function run()
    {
        Resource::create([
            'name' => [
                'en' => 'Alginite',
            ],
            'is_unlocked' => true,
            'frequency' => 1.0,
            'efficiency' => 1.05,
            'description' => [
                'en' => 'Finds on marsh planets.',
            ],
        ]);

        Resource::create([
            'name' => [
                'en' => 'Cobalt',
            ],
            'frequency' => 0.9,
            'efficiency' => 1.1,
            'description' => [
                'en' => 'Finds on sea planets.',
            ],
            'research_experience' => 10875,
            'research_cost' => 21750,
            'research_time' => 43500,
        ]);

        Resource::create([
            'name' => [
                'en' => 'Chronoton',
            ],
            'frequency' => 0.8,
            'efficiency' => 1.2,
            'description' => [
                'en' => 'Finds on desert planets.',
            ],
            'research_experience' => 21000,
            'research_cost' => 42000,
            'research_time' => 84000,
        ]);

        Resource::create([
            'name' => [
                'en' => 'Titanium',
            ],
            'frequency' => 0.5,
            'efficiency' => 1.5,
            'description' => [
                'en' => 'Finds on asteroids.',
            ],
            'research_experience' => 41375,
            'research_cost' => 82750,
            'research_time' => 165500,
        ]);

        Resource::create([
            'name' => [
                'en' => 'Selenium',
            ],
            'frequency' => 0.4,
            'efficiency' => 1.6,
            'description' => [
                'en' => 'Finds on volcanic planets.',
            ],
            'research_experience' => 72500,
            'research_cost' => 145000,
            'research_time' => 290000,
        ]);

        Resource::create([
            'name' => [
                'en' => 'Thorium',
            ],
            'frequency' => 0.2,
            'efficiency' => 1.8,
            'description' => [
                'en' => 'Finds on ice planets.',
            ],
            'research_experience' => 119250,
            'research_cost' => 238500,
            'research_time' => 477000,
        ]);

        Resource::create([
            'name' => [
                'en' => 'Adamantine',
            ],
            'frequency' => 0.05,
            'efficiency' => 1.95,
            'description' => [
                'en' => 'Finds on extreme planets.',
            ],
            'research_experience' => 183687,
            'research_cost' => 367375,
            'research_time' => 734750,
        ]);
    }
}
