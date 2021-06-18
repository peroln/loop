<?php

use App\Models\{User, Wallet, Language, Role};
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
            ->state(new Sequence(fn() => [
                'language_id'   => Language::whereShortcode('en')->firstOrFail()->id,
                'this_referral' => null,
                'role_id'       => Role::whereName('admin')->firstOrFail()->id,
            ]))
            ->has(Wallet::factory()->count(1))
            ->create();
    }
}
