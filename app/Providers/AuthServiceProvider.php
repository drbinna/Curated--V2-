<?php

namespace App\Providers;

use App\Policies\StoryPolicy;
use App\Policies\NotificationPolicy;
use App\Policies\CommentPolicy;
use App\Models\Story;
use App\Models\Notification;
use App\Models\Comment;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Story::class => StoryPolicy::class,
        Notification::class => NotificationPolicy::class,
        Comment::class => CommentPolicy::class,
    ];

    public function boot(): void
    {
        //
    }
}