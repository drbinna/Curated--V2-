<?php

namespace App\Http\Controllers;

use App\Models\Publication;
use Illuminate\Http\Request;

class PublicationController extends Controller
{
    public function index(Request $request)
    {
        $query = Publication::with('user');

        if ($request->has('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        $publications = $query->orderBy('name')->paginate(20);

        return response()->json([
            'success' => true,
            'data' => $publications,
        ]);
    }

    public function show(Publication $publication)
    {
        $publication->load('user', 'stories');
        
        return response()->json([
            'success' => true,
            'data' => $publication,
        ]);
    }

    public function sync(Publication $publication)
    {
        // TODO: Implement Substack RSS sync logic
        return response()->json([
            'success' => true,
            'message' => 'Sync initiated',
        ]);
    }
}


