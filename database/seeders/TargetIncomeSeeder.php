<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TargetIncomeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('target_incomes')->insert([
                [
                    'name' => 'referral',
                ], [
                    'name' => 'account',
                ]
            ]

        );
    }
}
