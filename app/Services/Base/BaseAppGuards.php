<?php

namespace App\Services\Base;

interface BaseAppGuards
{
    public const ADMIN = "admin";

    public const USER = "user";

    public const ALL_GUARDS = [self::USER, self::ADMIN];
}
