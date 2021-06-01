<?php

use App\Models\Language;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $quantity = 1;
        User::factory()
            ->count($quantity)
            ->state(new Sequence(
               fn() => ['language_id' => Language::all()->random(),'this_referral' => rand(1, $quantity)]
            ))
            ->has(Wallet::factory()->count(1))
            ->create();
    }
}
