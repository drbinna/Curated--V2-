<?php

namespace App\Http\Controllers;

use App\Models\Bookmark;
use App\Models\Story;
use Illuminate\Http\Request;

class BookmarkController extends Controller
{
    public function index(Request $request)
    {
        $bookmarks = auth()->user()->bookmarks()
            ->with('story.user', 'story.publication', 'story.categories')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return response()->json([
            'success' => true,
            'data' => $bookmarks,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'story_id' => 'required|exists:stories,id',
        ]);

        if (auth()->user()->hasBookmarked(Story::find($request->story_id))) {
            return response()->json([
                'success' => false,
                'error' => ['message' => 'Already bookmarked'],
            ], 400);
        }

        auth()->user()->bookmarks()->create(['story_id' => $request->story_id]);
        Story::find($request->story_id)->increment('save_count');

        return response()->json([
            'success' => true,
            'message' => 'Bookmarked',
        ], 201);
    }

    public function destroy(Story $story)
    {
        auth()->user()->bookmarks()->where('story_id', $story->id)->delete();
        $story->decrement('save_count');

        return response()->json([
            'success' => true,
            'message' => 'Unbookmarked',
        ]);
    }
}



