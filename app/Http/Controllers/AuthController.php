<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'user_type' => 'required|in:reader,writer',
            'device_name' => 'required|string',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'user_type' => $request->user_type,
        ]);

        $token = $user->createToken($request->device_name)->plainTextToken;

        return response()->json([
            'success' => true,
            'data' => [
                'user' => $user,
                'token' => $token,
                'token_type' => 'Bearer',
            ],
        ], 201);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            'device_name' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        $token = $user->createToken($request->device_name)->plainTextToken;

        return response()->json([
            'success' => true,
            'data' => [
                'user' => $user,
                'token' => $token,
                'token_type' => 'Bearer',
            ],
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Successfully logged out',
        ]);
    }

    public function logoutAll(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Logged out from all devices',
        ]);
    }

    public function me(Request $request)
    {
        $user = $request->user();
        $user->followers_count = $user->followers()->count();
        $user->following_count = $user->follows()->count();
        $user->stories_count = $user->stories()->count();

        return response()->json([
            'success' => true,
            'data' => $user,
        ]);
    }

    public function tokens(Request $request)
    {
        $currentToken = $request->user()->currentAccessToken();
        $tokens = $request->user()->tokens->map(function ($token) use ($currentToken) {
            return [
                'id' => $token->id,
                'name' => $token->name,
                'last_used_at' => $token->last_used_at,
                'created_at' => $token->created_at,
                'is_current' => $token->id === $currentToken->id,
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $tokens,
        ]);
    }

    public function updateProfile(Request $request)
    {
        $request->validate([
            'name' => 'sometimes|string|max:255',
            'username' => 'sometimes|string|max:100|unique:users,username,' . $request->user()->id,
            'bio' => 'nullable|string|max:1000',
            'avatar' => 'nullable|file|image|mimes:jpeg,jpg,png,gif,webp|mimetypes:image/jpeg,image/png,image/gif,image/webp|max:5120',
            'avatar_url' => 'nullable|string|max:500',
        ]);

        $user = $request->user();
        $updateData = $request->only(['name', 'username', 'bio']);

        // Handle avatar image upload
        if ($request->hasFile('avatar')) {
            $file = $request->file('avatar');
            
            // Extension whitelist (defense in depth)
            $allowedExtensions = ['jpeg', 'jpg', 'png', 'gif', 'webp'];
            $ext = strtolower($file->getClientOriginalExtension());
            if (!in_array($ext, $allowedExtensions, true)) {
                return response()->json([
                    'success' => false,
                    'error' => [
                        'message' => 'Unsupported file extension. Allowed: jpeg, jpg, png, gif, webp',
                        'code' => 'VALIDATION_ERROR',
                    ],
                ], 422);
            }

            // Delete old avatar if exists
            if ($user->avatar_url) {
                $oldPath = str_replace(config('app.url') . '/public/storage/', '', $user->avatar_url);
                if (Str::startsWith($oldPath, 'uploads/avatars/')) {
                    Storage::disk('public')->delete($oldPath);
                }
            }

            // Store new avatar
            $folder = 'uploads/avatars';
            $filename = Str::uuid()->toString() . '.' . $ext;
            $path = $file->storeAs($folder, $filename, 'public');

            // Generate full URL with APP_URL including /public prefix
            $appUrl = rtrim(config('app.url'), '/');
            $storagePath = '/public/storage/' . $path;
            $updateData['avatar_url'] = $appUrl . $storagePath;
        } elseif ($request->has('avatar_url')) {
            // Allow direct URL update (for backward compatibility or external URLs)
            $updateData['avatar_url'] = $request->avatar_url;
        }

        $user->update($updateData);

        return response()->json([
            'success' => true,
            'data' => $user->fresh(),
        ]);
    }

    public function destroyToken(Request $request, $tokenId)
    {
        $request->user()->tokens()->where('id', $tokenId)->delete();

        return response()->json([
            'success' => true,
            'message' => 'Token revoked',
        ]);
    }

    public function connectSubstack(Request $request)
    {
        $substackAuthUrl = 'https://substack.com/api/v1/auth?';
        $params = http_build_query([
            'client_id' => env('SUBSTACK_CLIENT_ID'),
            'redirect_uri' => env('SUBSTACK_REDIRECT_URI'),
            'response_type' => 'code',
            'scope' => 'read:user read:posts',
            'state' => csrf_token(),
        ]);

        return redirect($substackAuthUrl . $params);
    }

    public function substackCallback(Request $request)
    {
        // Handle OAuth callback
        return response()->json(['message' => 'Substack OAuth callback']);
    }

    public function disconnectSubstack(Request $request)
    {
        $request->user()->update([
            'substack_id' => null,
            'substack_access_token' => null,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Substack disconnected',
        ]);
    }

    public function saveCategoryInterests(Request $request)
    {
        $request->validate([
            'category_ids' => 'required|array',
            'category_ids.*' => 'required|string|exists:categories,id',
        ]);

        $user = $request->user();
        $user->update([
            'category_interests' => $request->category_ids,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Category interests saved successfully',
            'data' => [
                'user' => $user->fresh(),
            ],
        ]);
    }
}











