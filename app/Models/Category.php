<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;

class Category extends Model
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
        'name',
        'slug',
        'description',
        'icon',
        'color',
    ];

    public function stories(): BelongsToMany
    {
        return $this->belongsToMany(Story::class, 'story_categories');
    }

    public function followers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'category_follows');
    }
}

