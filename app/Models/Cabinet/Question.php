<?php

namespace App\Models\Cabinet;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Question extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'active',
        'approved',
    ];

    /**
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return HasMany
     */
    public function answers(): HasMany
    {
        return $this->hasMany(Answer::class);
    }

    /**
     * @return MorphMany
     */
    public function contents(): MorphMany
    {
        return $this->morphMany(Content::class, 'contentable');
    }

    public static function boot() {
        parent::boot();

        static::deleting(function($question) {
            $question->contents()->delete();
            $question->answers()->delete();
        });
    }
}
