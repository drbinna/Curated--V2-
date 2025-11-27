<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Story;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    // Get comments for a story (threaded structure)
    public function index(Story $story, Request $request)
    {
        $perPage = $request->get('per_page', 20);
        
        // Get top-level comments with eager-loaded replies (2 levels deep)
        $comments = Comment::forStory($story->id)
            ->topLevel()
            ->with([
                'user:id,name,username,avatar_url',
                'replies' => function ($query) {
                    $query->with([
                        'user:id,name,username,avatar_url',
                        'replies' => function ($q) {
                            $q->with('user:id,name,username,avatar_url')
                              ->orderBy('created_at', 'asc')
                              ->limit(3); // Load only first 3 nested replies
                        }
                    ])->orderBy('created_at', 'asc');
                }
            ])
            ->withCount('replies')
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);

        // Add is_liked flag for authenticated user
        $user = auth()->user();
        $comments->getCollection()->transform(function ($comment) use ($user) {
            return $this->addLikeStatus($comment, $user);
        });

        return response()->json([
            'success' => true,
            'data' => $comments,
        ]);
    }

    // Get replies for a specific comment (for "load more replies")
    public function replies(Comment $comment, Request $request)
    {
        $replies = $comment->replies()
            ->with('user:id,name,username,avatar_url')
            ->withCount('replies')
            ->orderBy('created_at', 'asc')
            ->paginate(20);

        $user = auth()->user();
        $replies->getCollection()->transform(function ($reply) use ($user) {
            return $this->addLikeStatus($reply, $user);
        });

        return response()->json([
            'success' => true,
            'data' => $replies,
        ]);
    }

    // Create a comment
    public function store(Request $request, Story $story)
    {
        $request->validate([
            'body' => 'required|string|max:5000',
            'parent_id' => 'nullable|uuid|exists:comments,id',
        ]);

        // If replying, verify parent belongs to same story
        if ($request->parent_id) {
            $parent = Comment::findOrFail($request->parent_id);
            if ($parent->story_id !== $story->id) {
                return response()->json([
                    'success' => false,
                    'error' => ['message' => 'Parent comment does not belong to this story'],
                ], 422);
            }
            
            // Optional: Limit nesting depth (e.g., max 5 levels)
            if ($parent->depth >= 5) {
                return response()->json([
                    'success' => false,
                    'error' => ['message' => 'Maximum reply depth reached'],
                ], 422);
            }
        }

        $comment = Comment::create([
            'story_id' => $story->id,
            'user_id' => auth()->id(),
            'parent_id' => $request->parent_id,
            'body' => $request->body,
        ]);

        $comment->load('user:id,name,username,avatar_url');

        // Increment story's comment count (add this field to stories table)
        $story->increment('comment_count');

        return response()->json([
            'success' => true,
            'data' => $comment,
            'message' => 'Comment added successfully',
        ], 201);
    }

    // Update a comment
    public function update(Request $request, Comment $comment)
    {
        $this->authorize('update', $comment);

        $request->validate([
            'body' => 'required|string|max:5000',
        ]);

        $comment->update([
            'body' => $request->body,
            'edited_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'data' => $comment,
        ]);
    }

    // Delete a comment
    public function destroy(Comment $comment)
    {
        $this->authorize('delete', $comment);

        $storyId = $comment->story_id;
        
        // Soft delete preserves thread structure
        $comment->delete();
        
        // Decrement story's comment count
        Story::find($storyId)?->decrement('comment_count');

        return response()->json([
            'success' => true,
            'message' => 'Comment deleted',
        ]);
    }

    // Like/unlike a comment
    public function toggleLike(Comment $comment)
    {
        $user = auth()->user();
        $existing = $comment->likes()->where('user_id', $user->id)->first();

        if ($existing) {
            $existing->delete();
            $comment->decrement('likes_count');
            $liked = false;
        } else {
            $comment->likes()->create(['user_id' => $user->id]);
            $comment->increment('likes_count');
            $liked = true;
        }

        return response()->json([
            'success' => true,
            'data' => [
                'is_liked' => $liked,
                'likes_count' => $comment->fresh()->likes_count,
            ],
        ]);
    }

    // Helper to add like status to comments
    private function addLikeStatus($comment, $user)
    {
        $comment->is_liked = $comment->isLikedBy($user);
        
        if ($comment->relationLoaded('replies')) {
            $comment->replies->transform(function ($reply) use ($user) {
                return $this->addLikeStatus($reply, $user);
            });
        }
        
        return $comment;
    }
}