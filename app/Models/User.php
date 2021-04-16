<?php

namespace App\Models;

use App\Enums\Tokens\TokenEnum;
use App\Models\Helpers\Authy2FAInterface;
use App\Models\Helpers\BaseUsersModelInterface;
use App\Models\Helpers\JWTAuthModel;
use App\Models\Tokens\Transactions;
use App\Models\User\Activity;
use App\Models\User\KYC;
use App\Traits\UsesUUID;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;

class User extends JWTAuthModel implements BaseUsersModelInterface, Authy2FAInterface
{
    use UsesUUID;

    use HasFactory;

    use Notifiable;

    public const PASSWORD_REGEX = '/(?=^.{8,}$)((?=.*\d)|(?=.*\W+))(?![.\n])(?=.*[A-Z])(?=.*[a-z])(?=.*[~!^(){}<>%@#&*+.,=_-]).*$/';

    public static $allRelations = [self::KYC_RELATION];

    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'active',
        'first_name',
        'last_name',
        'password',
        'email_confirmed',
        'blocked',
        'photo',
        'block_reasons',
        'reject_reasons',
        'remember_token',
        'last_login',
        'last_activity',
        'last_transaction',
        'username',
        'google_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'authy_id',
        'uuid',

    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * @var bool - Allows to customize adding urls to the beginning of a line in getAttribute()
     */
    public static bool $withoutUrl = false;

    /**
     * @param $authy_id
     */
    public function updateAuthyId($authy_id): void
    {
        if ($this->auhty_id !== $authy_id) {
            $this->authy_id = $authy_id;
            $this->save();
        }
    }

    public function receivesBroadcastNotificationsOn(): string
    {
        return 'users.' . $this->id;
    }

    /**
     * User has many activities
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function activities()
    {
        return $this->hasMany(Activity::class);
    }

    /**
     * Viewed tokens by user
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function token()
    {
        return $this->belongsToMany(Tokens::class, 'token_user');
    }

    /**
     * Purchased tokens by user
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function purchasedTokens()
    {
        return $this->hasMany(Tokens::class)->orderBy('updated_at', 'desc')->with(TokenEnum::$allRelations);
    }

    /**
     * User transactions
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function transactions()
    {
        return $this->hasMany(Transactions::class)->with('token');
    }

    /**
     * @inheritDoc
     */
    public function disable2FA(): void
    {
        $this->attributes['authy2fa_enabled'] = false;
        $this->save();
    }

    /**
     * @inheritDoc
     */
    public function enable2FA(): void
    {
        $this->attributes['authy2fa_enabled'] = true;
        $this->save();
    }

    /**
     * @param $photo
     *
     * @return string
     */
    public function getPhotoAttribute($photo)
    {
        if (is_null($photo) || self::$withoutUrl === true) {
            return $photo;
        }

        return config('app.domain') . '/storage' . $photo;
    }
}
