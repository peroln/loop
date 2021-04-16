<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class EmailConfirmation
 *
 * @package App\Models
 */
class EmailConfirmation extends Model
{
    protected $table = 'email_confirmations';

    protected $fillable = [
        'email',
        'token',
    ];
}
