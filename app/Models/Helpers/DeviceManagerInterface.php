<?php

namespace App\Models\Helpers;

use Illuminate\Database\Eloquent\Relations\HasMany;

interface DeviceManagerInterface
{
    public function devices(): HasMany;
}
