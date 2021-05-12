<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Http\Resources\User\UserResource;
use App\Models\Language;
use App\Models\User;
use App\Repositories\Base\Repository;


class UserRepository extends Repository
{
    public function model(): string
    {
        return User::class;
    }

    /**
     * @param string $address
     * @return UserResource
     */
    public function getUserByWallet(string $address): UserResource
    {
        return new UserResource($this->getModel()->whereHas('wallet', fn($q) => $q->where('address', $address))->first());
    }



    /**
     * @param array $params
     * @return array
     */
    public function createUserDataParams(array $params): array
    {
        $language_shortcode = $params['language'] ?? 'en';
        return [
            'user_name' => $params['user_name'] ?? 'Default User',
            'avatar' => '/some-image.jpg',
            'blocked_faq' => false,
            'language_id' => Language::where('shortcode', $language_shortcode)->first()->id,
            'this_referral' => $params['referrer_id'] ?? 1
        ];
    }


}
