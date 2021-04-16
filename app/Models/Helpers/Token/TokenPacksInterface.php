<?php

namespace App\Models\Helpers\Token;

interface TokenPacksInterface
{

    public const START_TIME_FORMAT = 'Y-m-d H:i';

    //Rarity types
    public const TYPE_GOLD = 'gold';

    public const TYPE_SILVER = 'silver';

    public const TYPE_BRONZE = 'bronze';

    //Types
    public const TYPE_DROP = 'drop';

    public const TYPE_BLOODBIN = 'bloodbin';

    //Payment statuses
    public const STATUS_PAID = 'paid';

    public const STATUS_AVAILABLE = 'available';
}
