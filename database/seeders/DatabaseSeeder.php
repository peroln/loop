<?php

use Illuminate\Database\Seeder;
use Database\Seeders\{LeagueSeeder, PlatformLevelSeeder, PlatformSeeder};

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run(): void
    {
        $this->call(\LanguageSeeder::class);
        $this->call(LeagueSeeder::class);
        $this->call(\UserSeeder::class);
        $this->call(PlatformLevelSeeder::class);
        $this->call(PlatformSeeder::class);
    }
}
