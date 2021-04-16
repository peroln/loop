<?php

namespace App\Models\Helpers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Contracts\JWTSubject;

abstract class JWTAuthModel extends Authenticatable implements JWTSubject
{
    public const PASSWORD_REGEX = '/(?=^.{8,}$)((?=.*\d)|(?=.*\W+))(?![.\n])(?=.*[A-Z])(?=.*[a-z])(?=.*[~!^(){}<>%@#&*+.,=_-]).*$/';

    use Notifiable;

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims(): array
    {
        return [];
    }

    public function invalidateToken(): bool
    {
        $this->remember_token = null;

        return $this->save();
    }

    public function setPasswordAttribute(string $password): void
    {
        $this->attributes['password'] = Hash::make($password);
    }

    public function fill(array $attributes): Model
    {
        if ($this instanceof Google2FAInterface) {
            $this->google2fa_secret = '';
        }

        return parent::fill($attributes);
    }
}
