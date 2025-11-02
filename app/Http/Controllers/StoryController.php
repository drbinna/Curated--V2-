<?php

namespace App\Http\Controllers;

use App\Models\Story;
use Illuminate\Http\Request;
use Carbon\Carbon;

class StoryController extends Controller
{
    public function index(Request $request)
    {
        $query = Story::with('user', 'categories');

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        $stories = $query->orderBy('published_at', 'desc')->paginate(20);

        return response()->json([
            'success' => true,
            'data' => $stories,
        ]);
    }

    public function show(Story $story)
    {
        $story->load('user', 'categories');
        $story->view_count++;
        $story->save();

        return response()->json([
            'success' => true,
            'data' => $story,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:500',
            'excerpt' => 'required|string',
            'image_url' => 'nullable|url',
            'substack_post_url' => 'required|url',
            'category_ids' => 'nullable|array',
            'category_ids.*' => 'exists:categories,id',
            'publish_now' => 'boolean',
        ]);

        $story = Story::create([
            'user_id' => auth()->id(),
            'title' => $request->title,
            'excerpt' => $request->excerpt,
            'image_url' => $request->image_url,
            'substack_post_url' => $request->substack_post_url,
            'published_at' => $request->publish_now ? now() : null,
            'expires_at' => $request->publish_now ? now()->addHours(48) : now()->addHours(48),
        ]);

        if ($request->has('category_ids')) {
            $story->categories()->attach($request->category_ids);
        }

        $story->load('categories');

        return response()->json([
            'success' => true,
            'data' => $story,
            'message' => 'Story created successfully',
        ], 201);
    }

    public function update(Request $request, Story $story)
    {
        $this->authorize('update', $story);

        $request->validate([
            'title' => 'sometimes|string|max:500',
            'excerpt' => 'sometimes|string',
            'image_url' => 'nullable|url',
            'category_ids' => 'nullable|array',
            'category_ids.*' => 'exists:categories,id',
        ]);

        $story->update($request->only(['title', 'excerpt', 'image_url']));

        // Update categories if provided
        if ($request->has('category_ids')) {
            $story->categories()->sync($request->category_ids);
        }

        $story->load('categories');

        return response()->json([
            'success' => true,
            'data' => $story->fresh(['categories']),
        ]);
    }

    public function destroy(Story $story)
    {
        $this->authorize('delete', $story);

        $story->delete();

        return response()->json([
            'success' => true,
            'message' => 'Story deleted',
        ], 204);
    }

    public function recordView(Story $story)
    {
        $story->views()->create([
            'user_id' => auth()->id(),
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        $story->increment('view_count');

        return response()->json(['success' => true]);
    }

    public function recordClick(Story $story)
    {
        $story->clicks()->create([
            'user_id' => auth()->id(),
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        $story->increment('click_count');

        return response()->json(['success' => true]);
    }

    public function trending(Request $request)
    {
        $stories = Story::with('user', 'categories')
            ->where('status', 'active')
            ->where('expires_at', '>', now())
            ->orderByDesc('view_count')
            ->orderByDesc('click_count')
            ->take(20)
            ->get();

        return response()->json([
            'success' => true,
            'data' => $stories,
        ]);
    }

    public function bar(Request $request)
    {
        $stories = Story::with('user', 'categories')
            ->where('status', 'active')
            ->where('expires_at', '>', now())
            ->orderBy('published_at', 'desc')
            ->take(10)
            ->get();

        return response()->json([
            'success' => true,
            'data' => $stories,
        ]);
    }

    public function myStories(Request $request)
    {
        $user = $request->user();

        $stories = $user->stories()
            ->with('categories')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return response()->json([
            'success' => true,
            'data' => $stories,
        ]);
    }
}



