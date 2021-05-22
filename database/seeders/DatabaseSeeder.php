<?php

use Illuminate\Database\Seeder;

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
        $this->call(\UserSeeder::class);
        $this->call(\Database\Seeders\PlatformLevelSeeder::class);
        $this->call(\Database\Seeders\PlatformSeeder::class);
    }
}
