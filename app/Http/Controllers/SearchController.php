<?php

namespace App\Http\Controllers;

use App\Models\Story;
use App\Models\User;
use App\Models\Publication;
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
            ->with('user', 'publication', 'categories')
            ->paginate(20);

        $users = User::where('name', 'like', '%' . $request->q . '%')
            ->orWhere('username', 'like', '%' . $request->q . '%')
            ->paginate(20);

        $publications = Publication::where('name', 'like', '%' . $request->q . '%')
            ->orWhere('description', 'like', '%' . $request->q . '%')
            ->with('user')
            ->paginate(20);

        return response()->json([
            'success' => true,
            'data' => [
                'stories' => $stories,
                'users' => $users,
                'publications' => $publications,
            ],
        ]);
    }

    public function stories(Request $request)
    {
        $request->validate(['q' => 'required|string']);

        $stories = Story::where('title', 'like', '%' . $request->q . '%')
            ->orWhere('excerpt', 'like', '%' . $request->q . '%')
            ->with('user', 'publication', 'categories')
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

    public function publications(Request $request)
    {
        $request->validate(['q' => 'required|string']);

        $publications = Publication::where('name', 'like', '%' . $request->q . '%')
            ->orWhere('description', 'like', '%' . $request->q . '%')
            ->with('user')
            ->paginate(20);

        return response()->json([
            'success' => true,
            'data' => $publications,
        ]);
    }
}

