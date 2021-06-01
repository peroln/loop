<?php

use Illuminate\Support\Facades\Broadcast;
use App\Models\Service\Platform;
use App\Models\Wallet;
/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

Broadcast::channel('debug', function () {
    \Illuminate\Support\Facades\Log::info('Chanel route');
    return 1;
});

Broadcast::channel('wallet.{walletId}', function ($user, $walletId) {
    return $user->id === Wallet::findOrFail($walletId)->id;
});

Broadcast::channel('debug', function () {
    return true;
});
