<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\StoryController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\FeedController;
use App\Http\Controllers\BookmarkController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\PublicationController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\AnalyticsController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\UploadController;
use Illuminate\Support\Facades\Route;

// Health check
Route::get('/health', function () {
    return response()->json(['status' => 'ok']);
});

// Authentication routes
Route::post('/auth/register', [AuthController::class, 'register']);
Route::post('/auth/login', [AuthController::class, 'login']);

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    
    // Auth routes
    Route::get('/auth/me', [AuthController::class, 'me']);
    Route::post('/auth/logout', [AuthController::class, 'logout']);
    Route::post('/auth/logout-all', [AuthController::class, 'logoutAll']);
    Route::get('/auth/tokens', [AuthController::class, 'tokens']);
    Route::delete('/auth/tokens/{tokenId}', [AuthController::class, 'destroyToken']);
    Route::put('/auth/profile', [AuthController::class, 'updateProfile']);
    
    // Substack OAuth
    Route::get('/auth/substack', [AuthController::class, 'connectSubstack']);
    Route::get('/auth/substack/callback', [AuthController::class, 'substackCallback']);
    Route::post('/auth/substack/disconnect', [AuthController::class, 'disconnectSubstack']);

    // Stories
    Route::get('/stories', [StoryController::class, 'index']);
    Route::get('/stories/bar', [StoryController::class, 'bar']);
    Route::get('/stories/trending', [StoryController::class, 'trending']);
    Route::post('/stories', [StoryController::class, 'store']);
    Route::get('/stories/{story}', [StoryController::class, 'show']);
    Route::put('/stories/{story}', [StoryController::class, 'update']);
    Route::delete('/stories/{story}', [StoryController::class, 'destroy']);
    Route::post('/stories/{story}/view', [StoryController::class, 'recordView']);
    Route::post('/stories/{story}/click', [StoryController::class, 'recordClick']);

    // Feed
    Route::get('/feed', [FeedController::class, 'index']);

    // Bookmarks
    Route::get('/bookmarks', [BookmarkController::class, 'index']);
    Route::post('/bookmarks', [BookmarkController::class, 'store']);
    Route::delete('/bookmarks/{story}', [BookmarkController::class, 'destroy']);

    // Users
    Route::get('/users/{user}', [UserController::class, 'show']);
    Route::post('/users/{user}/follow', [UserController::class, 'follow']);
    Route::delete('/users/{user}/follow', [UserController::class, 'unfollow']);
    Route::get('/users/{user}/followers', [UserController::class, 'followers']);
    Route::get('/users/{user}/following', [UserController::class, 'following']);
    Route::get('/users/{user}/stories', [UserController::class, 'stories']);

    // Categories
    Route::get('/categories', [CategoryController::class, 'index']);
    Route::get('/categories/{category}', [CategoryController::class, 'show']);
    Route::get('/categories/{category}/stories', [CategoryController::class, 'stories']);
    Route::post('/categories/{category}/follow', [CategoryController::class, 'follow']);
    Route::delete('/categories/{category}/follow', [CategoryController::class, 'unfollow']);

    // Publications
    Route::get('/publications', [PublicationController::class, 'index']);
    Route::get('/publications/{publication}', [PublicationController::class, 'show']);
    Route::post('/publications/{publication}/sync', [PublicationController::class, 'sync']);

    // Search
    Route::get('/search', [SearchController::class, 'index']);
    Route::get('/search/stories', [SearchController::class, 'stories']);
    Route::get('/search/users', [SearchController::class, 'users']);
    Route::get('/search/publications', [SearchController::class, 'publications']);

    // Analytics
    Route::get('/analytics/stories/{story}', [AnalyticsController::class, 'story']);
    Route::get('/analytics/dashboard', [AnalyticsController::class, 'dashboard']);
    Route::get('/analytics/audience', [AnalyticsController::class, 'audience']);

    // Notifications
    Route::get('/notifications', [NotificationController::class, 'index']);
    Route::put('/notifications/{notification}/read', [NotificationController::class, 'markAsRead']);
    Route::put('/notifications/read-all', [NotificationController::class, 'markAllAsRead']);
    Route::delete('/notifications/{notification}', [NotificationController::class, 'destroy']);

    // Upload
    Route::post('/upload/image', [UploadController::class, 'image']);
    Route::delete('/upload/image', [UploadController::class, 'deleteImage']);

});



