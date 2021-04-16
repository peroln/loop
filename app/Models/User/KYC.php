<?php

namespace App\Models\User;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KYC extends Model
{
    use HasFactory;

    protected $table = 'kyc_verification';

    protected $fillable = [
        'user_id',
        'birth_date',
        'country',
        'postal_address',
        'city',
        'street_address',
        'zip_code',
    ];

    protected $casts = [
        'user_id'        => 'integer',
        'birth_day'      => 'date_format:yyyy/mm/dd',
        'country'        => 'string',
        'postal_address' => 'string',
        'city'           => 'string',
        'zip_code'       => 'string',
        'street_address' => 'string',
    ];

    protected $hidden = ['id'];

    public const PENDING_STATUS = 'pending';

    public const ACCEPTED_STATUS = 'accepted';

    public const CANCELED_STATUS = 'canceled';

    public const UNVERIFIED = 'unverified';

    public static array  $statuses = [self::PENDING_STATUS, self::ACCEPTED_STATUS, self::CANCELED_STATUS];

    public static array $searchStatuses = [self::UNVERIFIED, self::PENDING_STATUS, self::ACCEPTED_STATUS, self::CANCELED_STATUS];

    public const DEFAULT_LANGUAGE = 'en';

    /**
     * Has one user
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Kyc has many documents
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function documents()
    {
        return $this->hasMany(KycDocuments::class, 'kyc_verification_id');
    }
}
