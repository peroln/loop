<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KycDocuments extends Model
{
    use HasFactory;

    public static bool $withoutUrl = false;

    protected $table = 'kyc_documents';

    protected $primaryKey = 'kyc_verification_id';

    public const AGE_PROOF = 'age_proof';

    public const DRIVER_LICENSE = 'driver_license';

    public static array $filetypes = [self::AGE_PROOF, self::DRIVER_LICENSE];

    protected $fillable = [
        'kyc_verification_id',
        'type',
        'file',
    ];

    protected $hidden = ['id', 'kyc_verification_id'];

    /**
     * Document has one kyc
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function kyc()
    {
        return $this->belongsTo(KYC::class, 'id');
    }

    /**
     * @param $photo
     *
     * @return string
     */
    public function getFileAttribute($photo)
    {
        if (is_null($photo) || self::$withoutUrl === true) {
            return $photo;
        }

        return config('app.domain') . '/storage' . $photo;
    }
}
