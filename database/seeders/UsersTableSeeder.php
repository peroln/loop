<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use App\Models\User;
use Carbon\Carbon;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();

        for ($i = 1; $i <= 30; $i++) {
            $lang = ['en', 'es', 'ru'];
            $referralId = random_int(1, 10);
            $users[] = [
                'user_name'         => $faker->userName,
                'avatar'            => 'd9sf8h7d.jpg',
                'blocked_faq'       => false,
                'lang'              => Arr::random($lang),
                'this_referral'     => $referralId,
                'created_at'        => Carbon::now()->addMinutes($i)->format('Y-m-d H:i:s'),
                'updated_at'        => Carbon::now()->addMinutes($i + 2)->format('Y-m-d H:i:s'),

            ];
        }

        DB::table('users')->insert($users);
    }
}
