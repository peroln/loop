<?php


namespace App\Services;


use App\Models\Service\Platform;
use App\Models\Service\PlatformLevel;
use Illuminate\Support\Facades\Auth;

class PlatformHandlerService
{
    public function createNewSubscriber(int $platform_level_id)
    {
        $current_free_platform = $this->findCurrentPlatformOwner($platform_level_id);
        $current_free_platform->wallets()->attach(Auth::user()->id);
        for($i=0; $i<3; $i++){
            Platform::create([
                'wallet_id' => Auth::user()->id,
                'platform_level_id' => $platform_level_id
            ]);
        }
        return $current_free_platform->wallet->address;
    }

    private function findCurrentPlatformOwner(int $platform_level_id)
    {
        do {
            $platform = Platform::where('active', 1)->firstOrFail();
        } while (!$this->checkCountSubscribers($platform, $platform_level_id));
        return $platform;

    }

    private function checkCountSubscribers(Platform $platform, int $platform_level_id)
    {
        $max_count = PlatformLevel::find($platform_level_id)->count_platform_subscribers;
        $subscribers_now = $platform->wallets()->count();
        if ($subscribers_now >= $max_count) {
            $platform->active = 0;
            $platform->save();
            return false;
        }
        return true;
    }
}
