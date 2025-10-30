<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class UploadController extends Controller
{
    public function image(Request $request)
    {
        $request->validate([
            'image' => 'required|file|image|mimes:jpeg,jpg,png,gif,webp|mimetypes:image/jpeg,image/png,image/gif,image/webp|max:5120',
            'type' => 'required|string|in:story,avatar,publication',
        ]);

        $file = $request->file('image');

        // Extension whitelist (defense in depth)
        $allowedExtensions = ['jpeg', 'jpg', 'png', 'gif', 'webp'];
        $ext = strtolower($file->getClientOriginalExtension());
        if (! in_array($ext, $allowedExtensions, true)) {
            return response()->json([
                'success' => false,
                'error' => [
                    'message' => 'Unsupported file extension.',
                    'code' => 'VALIDATION_ERROR',
                ],
            ], 422);
        }

        // Build safe filename
        $folder = "uploads/{$request->type}s"; // uploads/stories, uploads/avatars, uploads/publications
        $filename = Str::uuid()->toString().".".$ext;

        // Store on local public disk
        $path = $file->storeAs($folder, $filename, 'public');

        return response()->json([
            'success' => true,
            'data' => [
                'url' => Storage::disk('public')->url($path),
                'path' => $path,
            ],
        ]);
    }

    public function deleteImage(Request $request)
    {
        $request->validate([
            'path' => 'required|string',
        ]);

        // Only allow deletion within our uploads directories
        if (! Str::startsWith($request->path, ['uploads/stories', 'uploads/avatars', 'uploads/publications'])) {
            return response()->json([
                'success' => false,
                'error' => [
                    'message' => 'Invalid path.',
                    'code' => 'VALIDATION_ERROR',
                ],
            ], 422);
        }

        Storage::disk('public')->delete($request->path);

        return response()->json([
            'success' => true,
            'message' => 'Image deleted',
        ]);
    }
}


