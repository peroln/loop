<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PlatformLevelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('platform_levels')->insert([
            [
                'name'                       => 'Level 1',
                'cost_buy'                   => 100,
                'cost_activation'            => 100,
                'cost_gaz'                   => 30,
                'count_platform_subscribers' => 3
            ],
            [
                'name'                       => 'Level 2',
                'cost_buy'                   => 50,
                'cost_activation'            => 200,
                'cost_gaz'                   => 30,
                'count_platform_subscribers' => 3
            ],
            [
                'name'                       => 'Level 3',
                'cost_buy'                   => 100,
                'cost_activation'            => 400,
                'cost_gaz'                   => 30,
                'count_platform_subscribers' => 3
            ],
            [
                'name'                       => 'Level 4',
                'cost_buy'                   => 200,
                'cost_activation'            => 800,
                'cost_gaz'                   => 30,
                'count_platform_subscribers' => 3
            ],
            [
                'name'                       => 'Level 5',
                'cost_buy'                   => 500,
                'cost_activation'            => 1500,
                'cost_gaz'                   => 200000,
                'count_platform_subscribers' => 3
            ],
            [
                'name'                       => 'Level 6',
                'cost_buy'                   => 1000,
                'cost_activation'            => 3000,
                'cost_gaz'                   => 30,
                'count_platform_subscribers' => 3
            ],
            [
                'name'                       => 'Level 7',
                'cost_buy'                   => 3000,
                'cost_activation'            => 5000,
                'cost_gaz'                   => 30,
                'count_platform_subscribers' => 3
            ],
            [
                'name'                       => 'Level 8',
                'cost_buy'                   => 3500,
                'cost_activation'            => 10000,
                'cost_gaz'                   => 30,
                'count_platform_subscribers' => 3
            ],
            [
                'name'                       => 'Level 9',
                'cost_buy'                   => 7000,
                'cost_activation'            => 20000,
                'cost_gaz'                   => 30,
                'count_platform_subscribers' => 3
            ],
            [
                'name'                       => 'Level 10',
                'cost_buy'                   => 200000,
                'cost_activation'            => 200000,
                'cost_gaz'                   => 200000,
                'count_platform_subscribers' => 3
            ],
            [
                'name'                       => 'Level 11',
                'cost_buy'                   => 14000,
                'cost_activation'            => 40000,
                'cost_gaz'                   => 30,
                'count_platform_subscribers' => 3
            ],
            [
                'name'                       => 'Level 12',
                'cost_buy'                   => 55000,
                'cost_activation'            => 160000,
                'cost_gaz'                   => 30,
                'count_platform_subscribers' => 3
            ],
            [
                'name'                       => 'Level 13',
                'cost_buy'                   => 110000,
                'cost_activation'            => 320000,
                'cost_gaz'                   => 30,
                'count_platform_subscribers' => 3
            ],
            [
                'name'                       => 'Level 14',
                'cost_buy'                   => 220000,
                'cost_activation'            => 640000,
                'cost_gaz'                   => 30,
                'count_platform_subscribers' => 3
            ],
            [
                'name'                       => 'Level 15',
                'cost_buy'                   => 440000,
                'cost_activation'            => 1280000,
                'cost_gaz'                   => 30,
                'count_platform_subscribers' => 3
            ]
        ]);
    }
}
