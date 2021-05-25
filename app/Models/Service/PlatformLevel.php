<?php

namespace App\Models\Service;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlatformLevel extends Model
{
    use HasFactory;

    public function reactivation(){
        return $this->hasMany(Reactivation::class);
    }
}
