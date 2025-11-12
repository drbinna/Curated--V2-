<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::all();

        return response()->json([
            'success' => true,
            'data' => $categories,
        ]);
    }

    public function show(Category $category)
    {
        return response()->json([
            'success' => true,
            'data' => $category,
        ]);
    }

    public function stories(Category $category, Request $request)
    {
        $stories = $category->stories()
            ->with('user', 'publication')
            ->where('status', 'active')
            ->where('expires_at', '>', now())
            ->orderBy('published_at', 'desc')
            ->paginate(20);

        return response()->json([
            'success' => true,
            'data' => $stories,
        ]);
    }

    public function follow(Category $category)
    {
        if (auth()->user()->categories()->where('id', $category->id)->exists()) {
            return response()->json([
                'success' => false,
                'error' => ['message' => 'Already following this category'],
            ], 400);
        }

        auth()->user()->categories()->attach($category->id);

        return response()->json([
            'success' => true,
            'message' => 'Following category',
        ], 201);
    }

    public function unfollow(Category $category)
    {
        auth()->user()->categories()->detach($category->id);

        return response()->json([
            'success' => true,
            'message' => 'Unfollowed category',
        ]);
    }
}











