<?php

namespace App\Enums\Admin;

class PermissionsEnum
{
    //Users Management
    public const USERS_ENTITY       = 'UsersManagement';
    public const USERS_SHOW         = 'users_show';
    public const USERS_BLOCK        = 'users_block';
    public const USERS_ACTIVITY     = 'users_activity';
    public const USERS_TRANSACTIONS = 'users_transactions';

    //KYC manage
    public const KYC_ENTITY = 'KycManagement';
    public const KYC_ACCEPT = 'kyc_accept';
    public const KYC_REJECT = 'kyc_reject';


    /** @var array Permissions array */
    public const PERMISSIONS_LIST = [
        ['entity' => self::USERS_ENTITY, 'name' => self::USERS_SHOW],
        ['entity' => self::USERS_ENTITY, 'name' => self::USERS_BLOCK],
        ['entity' => self::USERS_ENTITY, 'name' => self::USERS_ACTIVITY],
        ['entity' => self::USERS_ENTITY, 'name' => self::USERS_TRANSACTIONS],
        ['entity' => self::KYC_ENTITY, 'name' => self::KYC_ACCEPT],
        ['entity' => self::KYC_ENTITY, 'name' => self::KYC_REJECT],
    ];
}
