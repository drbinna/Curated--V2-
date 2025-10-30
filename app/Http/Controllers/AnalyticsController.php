<?php

namespace App\Http\Controllers;

use App\Models\Story;
use Illuminate\Http\Request;

class AnalyticsController extends Controller
{
    public function story(Story $story)
    {
        $metrics = [
            'views' => $story->view_count,
            'clicks' => $story->click_count,
            'saves' => $story->save_count,
            'shares' => $story->share_count,
            'click_through_rate' => $story->view_count > 0 
                ? round(($story->click_count / $story->view_count) * 100, 2)
                : 0,
        ];

        return response()->json([
            'success' => true,
            'data' => [
                'story_id' => $story->id,
                'title' => $story->title,
                'metrics' => $metrics,
            ],
        ]);
    }

    public function dashboard(Request $request)
    {
        $user = auth()->user();
        
        $stats = [
            'total_stories' => $user->stories()->count(),
            'total_views' => $user->stories()->sum('view_count'),
            'total_clicks' => $user->stories()->sum('click_count'),
            'total_saves' => $user->stories()->sum('save_count'),
            'active_stories' => $user->stories()->where('status', 'active')->count(),
            'followers' => $user->followers()->count(),
            'following' => $user->follows()->count(),
        ];

        return response()->json([
            'success' => true,
            'data' => $stats,
        ]);
    }

    public function audience(Request $request)
    {
        $user = auth()->user();
        
        $stories = $user->stories()
            ->orderBy('view_count', 'desc')
            ->take(10)
            ->get(['id', 'title', 'view_count', 'click_count']);

        return response()->json([
            'success' => true,
            'data' => $stories,
        ]);
    }
}


