<?php

namespace App\Models;

use App\Models\Helpers\DeviceCheckInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserDevices extends Model implements DeviceCheckInterface
{
    use HasFactory;

    /**
     * @var string
     */
    protected $table = "user_devices";

    /**
     * @var string[]
     */
    protected $fillable = [
        'device_id',
        'user_id',
        'code',
        'verify',
    ];

    /**
     * @param \Illuminate\Database\Eloquent\Builder $builder
     * @param int                                   $ownerId
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOwnerDevice(Builder $builder, int $ownerId): Builder
    {
        return $builder->where('user_id', $ownerId);
    }

    public function isVerified(): bool
    {
        return (bool) $this->attributes['verify'];
    }

    public function getCodeHash(): ?string
    {
        return $this->attributes['code'] ?? null;
    }
}
