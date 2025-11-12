<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureCategoryInterests
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        // Check if user has category interests set
        if (!$user || empty($user->category_interests) || !is_array($user->category_interests) || count($user->category_interests) === 0) {
            return response()->json([
                'success' => false,
                'error' => [
                    'message' => 'Please select your category interests to continue',
                    'code' => 'CATEGORY_INTERESTS_REQUIRED',
                ],
            ], 403);
        }

        return $next($request);
    }
}

