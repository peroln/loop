<?php

namespace Database\Seeders;

use App\Models\Service\PlatformLevel;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PlatformSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $platform_levels_array_ids = PlatformLevel::pluck('id')->toArray();
        $res_arr = array_map(function ($value){
            return ['wallet_id' => 1, 'platform_level_id' => $value, 'created_at' => now()];
        }, $platform_levels_array_ids);
        DB::table('platforms')->insert($res_arr);
    }
}
