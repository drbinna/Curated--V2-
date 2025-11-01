<?php

namespace App\Http\Controllers;

use App\Models\Story;
use App\Models\User;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'q' => 'required|string',
        ]);

        $stories = Story::where('title', 'like', '%' . $request->q . '%')
            ->orWhere('excerpt', 'like', '%' . $request->q . '%')
            ->with('user', 'categories')
            ->paginate(20);

        $users = User::where('name', 'like', '%' . $request->q . '%')
            ->orWhere('username', 'like', '%' . $request->q . '%')
            ->paginate(20);

        return response()->json([
            'success' => true,
            'data' => [
                'stories' => $stories,
                'users' => $users,
            ],
        ]);
    }

    public function stories(Request $request)
    {
        $request->validate(['q' => 'required|string']);

        $stories = Story::where('title', 'like', '%' . $request->q . '%')
            ->orWhere('excerpt', 'like', '%' . $request->q . '%')
            ->with('user', 'categories')
            ->paginate(20);

        return response()->json([
            'success' => true,
            'data' => $stories,
        ]);
    }

    public function users(Request $request)
    {
        $request->validate(['q' => 'required|string']);

        $users = User::where('name', 'like', '%' . $request->q . '%')
            ->orWhere('username', 'like', '%' . $request->q . '%')
            ->paginate(20);

        return response()->json([
            'success' => true,
            'data' => $users,
        ]);
    }
}





