<?php

namespace  App\Models\Helpers\Token;

interface BaseTokensModelInterface
{

    public const TYPE_DROP = 'drop';

    public const TYPE_BLOODBIN = 'bloodbin';

    public const CURRENCY_REGEX = '/^(?=.*[1-9])\d+(\.\d{1,2})?$/';

    public const STATUS_ACTIVE = 'active';

    public const STATUS_SOLD = 'sold_out';

    public const  STATUS_LOADING = 'loading';

    public const START_TIME_FORMAT = 'Y-m-d H:i';

    //Image dimensions
    public const WIDTH = 274;

    public const HEIGHT = 437;
}
