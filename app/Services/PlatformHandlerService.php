<?php


namespace App\Services;


use App\Models\Service\Platform;
use App\Models\Service\PlatformLevel;
use App\Models\Service\Reactivation;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PlatformHandlerService
{
    /**
     * @param  int  $wallet_id
     * @param  int  $platform_level_id
     *
     * @return string
     */
    public function createNewSubscriber(int $wallet_id, int $platform_level_id = 1): string
    {
        $current_free_platform = $this->findCurrentPlatformOwner($platform_level_id);
        $current_free_platform->wallets()->attach($wallet_id);
        Platform::create([
            'wallet_id'         => $wallet_id,
            'platform_level_id' => $platform_level_id,
            'created_at'        => now(),
        ]);

        return $current_free_platform->wallet->address;
    }

    /**
     * @param  int  $platform_level_id
     *
     * @return Platform
     */
    private function findCurrentPlatformOwner(int $platform_level_id): Platform
    {
        do {
            $platform = Platform::where('active', 1)->where('platform_level_id', $platform_level_id)->firstOrFail();
        } while (!$this->checkCountSubscribers($platform, $platform_level_id));
        return $platform;

    }

    /**
     * @param  Platform  $platform
     * @param  int       $platform_level_id
     *
     * @return bool
     */
    private function checkCountSubscribers(Platform $platform, int $platform_level_id): bool
    {
        $max_count       = PlatformLevel::find($platform_level_id)->count_platform_subscribers;
        $subscribers_now = $platform->wallets()->count();
        if ($subscribers_now >= $max_count) {
            $platform->active = 0;
            $platform->save();
            return false;
        }
        return true;
    }

    /**
     * @param  int  $platform_level_id
     *
     * @return bool
     * @throws \Throwable
     */
    public function reactivationPlatform(int $wallet_id, int $platform_level_id): bool
    {
        $reactivation_model = Reactivation::firstOrNew([
            'platform_level_id' => $platform_level_id,
            'wallet_id'         => $wallet_id,
        ]);
        $reactivation_model->count++;
        DB::beginTransaction();
        try {
            $reactivation_model->save();
            Platform::create([
                'platform_level_id' => $platform_level_id,
                'wallet_id'         => $wallet_id,
                'created_at'        => now(),
            ]);
            DB::commit();
            return true;
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error($e->getMessage());
            return false;
        }
    }
}
