<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailConfirmation extends Model
{
    use HasFactory;

    /**
     * @var string
     */
    protected $table = 'admin_email_confirmation';

    /**
     * @var array
     */
    protected $fillable = [
        'admin_id',
        'email',
        'token'
    ];
}
