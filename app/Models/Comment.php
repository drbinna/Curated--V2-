<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Comment extends Model
{
    use HasFactory, SoftDeletes;

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

        static::created(function ($comment) {
            $comment->updatePath();
        });
    }

    protected $fillable = [
        'story_id',
        'user_id',
        'parent_id',
        'body',
        'path',
        'depth',
    ];

    protected $casts = [
        'depth' => 'integer',
        'replies_count' => 'integer',
        'likes_count' => 'integer',
        'edited_at' => 'datetime',
    ];

    public function story(): BelongsTo
    {
        return $this->belongsTo(Story::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Comment::class, 'parent_id');
    }

    public function replies(): HasMany
    {
        return $this->hasMany(Comment::class, 'parent_id');
    }

    public function likes(): HasMany
    {
        return $this->hasMany(CommentLike::class);
    }

    public function updatePath(): void
    {
        if ($this->parent_id) {
            $parent = $this->parent;
            $this->path = $parent->path . '/' . $this->id;
            $this->depth = $parent->depth + 1;
            $parent->increment('replies_count');
        } else {
            $this->path = $this->id;
            $this->depth = 0;
        }
        
        $this->saveQuietly();
    }

    public function ancestors()
    {
        if (!$this->path) return collect();
        
        $ancestorIds = explode('/', $this->path);
        array_pop($ancestorIds);
        
        return Comment::whereIn('id', $ancestorIds)->get();
    }

    public function descendants()
    {
        return Comment::where('path', 'LIKE', $this->path . '/%')
            ->orderBy('path')
            ->get();
    }

    public function scopeTopLevel($query)
    {
        return $query->whereNull('parent_id');
    }

    public function scopeForStory($query, $storyId)
    {
        return $query->where('story_id', $storyId);
    }

    public function isLikedBy(?User $user): bool
    {
        if (!$user) return false;
        return $this->likes()->where('user_id', $user->id)->exists();
    }
}