<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

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
        'name',
        'email',
        'password',
        'username',
        'bio',
        'avatar_url',
        'user_type',
        'category_interests',
        'substack_id',
        'substack_access_token',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'substack_access_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'category_interests' => 'array',
    ];

    public function stories(): HasMany
    {
        return $this->hasMany(Story::class);
    }

    public function follows(): HasMany
    {
        return $this->hasMany(Follow::class, 'follower_id');
    }

    public function followers(): HasMany
    {
        return $this->hasMany(Follow::class, 'following_id');
    }

    public function bookmarks(): HasMany
    {
        return $this->hasMany(Bookmark::class);
    }

    public function notifications(): HasMany
    {
        return $this->hasMany(Notification::class);
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'category_follows');
    }

    public function isFollowing(User $user): bool
    {
        return $this->follows()->where('following_id', $user->id)->exists();
    }

    public function hasBookmarked(Story $story): bool
    {
        return $this->bookmarks()->where('story_id', $story->id)->exists();
    }

    /**
     * Get the route key for the model.
     * This allows route model binding to work with username instead of ID
     */
    public function getRouteKeyName(): string
    {
        return 'username';
    }

    /**
     * Retrieve the model for route model binding.
     * Supports both UUID and username lookup
     */
    public function resolveRouteBinding($value, $field = null)
    {
        // Try to find by username first (if it looks like a username)
        // Otherwise try by ID (UUID)
        $user = $this->where('username', $value)->first();
        
        if (!$user) {
            $user = $this->where('id', $value)->first();
        }
        
        return $user;
    }
}

