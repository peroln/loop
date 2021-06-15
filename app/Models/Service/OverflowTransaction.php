<?php

namespace App\Models\Service;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

class OverflowTransaction extends Pivot
{
    use HasFactory;
    protected $fillable =[
      'transaction_id',
      'overflow_id',
      'created_at'
    ];
    const CREATED_AT = null;


}
