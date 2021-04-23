<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Swagger\Annotations as SWG;

class BaseModel extends Model
{
    protected $primaryKey = 'id';
    protected $guarded = [];
    public $timestamps = false;
    protected string $id;
    protected string $created_at;
    protected string $updated_at;

    protected static function boot()
    {
        static::creating(function ($model) {
            if (!$model->getKey()) {
                $uuid = (string)Str::uuid();
                $model->{$model->getKeyName()} = $uuid;
                $model->setAttribute($model->getKeyName(), $uuid);
            }
        });
        parent::boot();
    }

    public function getIncrementing()
    {
        return false;
    }

    public function getKeyType()
    {
        return 'string';
    }
}
