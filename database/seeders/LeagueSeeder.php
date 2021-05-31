<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LeagueSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('leagues')->insert([
                [
                    'name' => 'Bronze',
                ], [
                    'name' => 'Silver',
                ], [
                    'name' => 'Gold',
                ], [
                    'name' => 'Platinum'
                ], [
                    'name' => 'Diamond'
                ]
            ]

        );
    }
}
