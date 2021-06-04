<?php


namespace App\Services\EventsHandlers;


use App\Models\Wallet;
use Illuminate\Support\Arr;

class AddReferralLinkHandler extends BaseEventsHandler
{
    const EVENT_NAME = 'AddedReferralLink';

    /**
     * @param array $event
     * @return bool|array
     */
    public function extractDataFromTransaction(array $event): bool|array
    {
        $referral_link = Arr::get($event, 'result.link');
        $referral_address = $this->hexString2Base58(Arr::get($event, 'result.referral'));
        return compact(
            'referral_address',
            'referral_link'
        );
    }

    /**
     * @param array $params
     */
    public function createNewResource(array $params): void
    {
        $wallet = Wallet::where('address', Arr::get($params, 'referral_address'))->firstOrFail();
        $wallet->referral_link = Arr::get($params, 'referral_link', '');
        $wallet->save();
    }
}
