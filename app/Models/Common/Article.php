<?php

namespace App\Models\Common;

use App\Models\Cabinet\Content;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Article extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'status',
    ];

    /**
     * @return MorphMany
     */
    public function contents(): MorphMany
    {
        return $this->morphMany(Content::class, 'contentable');
    }

    /**
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public static function boot()
    {
        parent::boot();
        static::deleting(function ($article) {
            $article->contents()->delete();
        });
    }
}
