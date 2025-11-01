<?php

namespace App\Http\Controllers;

use App\Models\Story;
use Illuminate\Http\Request;

class FeedController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $query = Story::with('user', 'publication', 'categories')
            ->where('status', 'active')
            ->where('expires_at', '>', now());

        // Filter by category
        if ($request->has('category')) {
            $query->whereHas('categories', function ($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        // Filter by followed users
        if ($request->filter === 'following' && $user) {
            $followingIds = $user->follows()->pluck('following_id');
            $query->whereIn('user_id', $followingIds);
        }

        $stories = $query->orderBy('published_at', 'desc')->paginate(20);

        // Mark stories as bookmarked
        if ($user) {
            foreach ($stories->items() as $story) {
                $story->is_bookmarked = $user->hasBookmarked($story);
                $story->is_viewed = $story->views()->where('user_id', $user->id)->exists();
            }
        }

        return response()->json([
            'success' => true,
            'data' => $stories,
        ]);
    }
}





