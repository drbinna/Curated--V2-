<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;

class Publication extends Model
{
    use HasFactory;

    protected $keyType = 'string';
    public $incrementing = false;

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->{$model->getKeyName()})) {
                $model->{$model->getKeyName()} = (string) Str::uuid();
            }
        });
    }

    protected $fillable = [
        'user_id',
        'name',
        'slug',
        'description',
        'substack_url',
        'image_url',
        'verified',
        'rss_feed_url',
        'last_sync_at',
        'subscriber_count',
    ];

    protected $casts = [
        'verified' => 'boolean',
        'last_sync_at' => 'datetime',
        'subscriber_count' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function stories(): HasMany
    {
        return $this->hasMany(Story::class);
    }

    public function followers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'publication_follows');
    }

    public function storyFollowers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'publication_follows', 'publication_id', 'user_id');
    }
}

