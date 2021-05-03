<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Http\Resources\User\UserResource;
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
}
