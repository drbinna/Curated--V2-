<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function show(User $user)
    {
        $user->load('stories', 'publications');
        $user->followers_count = $user->followers()->count();
        $user->following_count = $user->follows()->count();
        $user->stories_count = $user->stories()->count();
        $user->is_following = auth()->user() ? auth()->user()->isFollowing($user) : false;

        return response()->json([
            'success' => true,
            'data' => $user,
        ]);
    }

    public function follow(User $user)
    {
        if (auth()->user()->id === $user->id) {
            return response()->json([
                'success' => false,
                'error' => ['message' => 'Cannot follow yourself'],
            ], 400);
        }

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
        $stories = $user->stories()->with('publication', 'categories')->paginate(20);

        return response()->json([
            'success' => true,
            'data' => $stories,
        ]);
    }
}



