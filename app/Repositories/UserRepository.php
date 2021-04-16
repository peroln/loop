<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Enums\KYC\KycEnum;
use App\Models\User;
use App\Models\User\KYC;
use App\Repositories\Base\Repository;
use Illuminate\Database\Eloquent\Builder;

class UserRepository extends Repository
{
    public function model(): string
    {
        return User::class;
    }
}
