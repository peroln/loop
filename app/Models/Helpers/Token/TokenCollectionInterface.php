<?php

namespace App\Models\Helpers\Token;

interface TokenCollectionInterface
{
    public const START_TIME_FORMAT = 'Y-m-d H:i';

    // Types
    public const TYPE_BLOODBIN = 'bloodbin';
    public const TYPE_DROP     = 'drop';

    //Collections types
    public const COLLECTION_TYPE_GOLD     = 'gold';
    public const COLLECTION_TYPE_GOLD_QTY = 5;

    public const COLLECTION_TYPE_SILVER     = 'silver';
    public const COLLECTION_TYPE_SILVER_QTY = 25;

    public const COLLECTION_TYPE_BRONZE     = 'bronze';
    public const COLLECTION_TYPE_BRONZE_QTY = 100;

    //Upload statuses
    public const UPLOAD_STATUS_PROCESS = 'process';
    public const UPLOAD_STATUS_LOADED  = 'loaded';
}
