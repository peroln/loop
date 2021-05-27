<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommandRefRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'command_id',
        'reference_id',
        'order',
        'status'
    ];
}
