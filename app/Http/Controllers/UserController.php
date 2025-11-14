<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function show(User $user, Request $request)
    {
        // Get all user stories with categories
        // By default, show all stories. Can filter by status if needed
        $storiesQuery = $user->stories()->with('categories');
        
        // Optional filter by status (active, expired, archived)
        if ($request->has('status')) {
            $storiesQuery->where('status', $request->status);
        }
        
        $stories = $storiesQuery->orderBy('published_at', 'desc')->get();

        // Calculate counts
        $followersCount = $user->followers()->count();
        $followingCount = $user->follows()->count();
        $storiesCount = $stories->count();

        // Check if authenticated user is following this user
        $isFollowing = auth()->user() ? auth()->user()->isFollowing($user) : false;

        // Build response with all required information
        $data = [
            'id' => $user->id,
            'name' => $user->name,
            'username' => $user->username,
            'email' => $user->email,
            'bio' => $user->bio,
            'avatar_url' => $user->avatar_url,
            'user_type' => $user->user_type,
            'followers_count' => $followersCount,
            'following_count' => $followingCount,
            'stories_count' => $storiesCount,
            'is_following' => $isFollowing,
            'stories' => $stories,
            'created_at' => $user->created_at,
            'updated_at' => $user->updated_at,
        ];

        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }

    public function follow(User $user)
    {
        if (auth()->user()->isFollowing($user)) {
            return response()->json([
                'success' => false,
                'error' => ['message' => 'Already following this user'],
            ], 400);
        }

        auth()->user()->follows()->create(['following_id' => $user->id]);

        return response()->json([
            'success' => true,
            'message' => 'Following user',
        ], 201);
    }

    public function unfollow(User $user)
    {
        auth()->user()->follows()->where('following_id', $user->id)->delete();

        return response()->json([
            'success' => true,
            'message' => 'Unfollowed user',
        ]);
    }

    public function followers(User $user)
    {
        $followers = $user->followers()->with('follower')->paginate(20);

        return response()->json([
            'success' => true,
            'data' => $followers,
        ]);
    }

    public function following(User $user)
    {
        $following = $user->follows()->with('following')->paginate(20);

        return response()->json([
            'success' => true,
            'data' => $following,
        ]);
    }

    public function stories(User $user)
    {
        $stories = $user->stories()->with('categories')->paginate(20);

        return response()->json([
            'success' => true,
            'data' => $stories,
        ]);
    }
}





