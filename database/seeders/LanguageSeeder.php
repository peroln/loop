<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LanguageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('languages')->insert([
                [
                    'name' => 'English',
                    'shortcode' => 'en',
                ], [
                    'name' => 'Spanish',
                    'shortcode' => 'es',
                ], [
                    'name' => 'Russian',
                    'shortcode' => 'ru',
                ]
            ]

        );
    }
}
