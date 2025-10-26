<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UploadController extends Controller
{
    public function image(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120',
            'type' => 'required|string|in:story,avatar,publication',
        ]);

        $file = $request->file('image');
        $path = $file->store("uploads/{$request->type}s", 's3');

        return response()->json([
            'success' => true,
            'data' => [
                'url' => Storage::disk('s3')->url($path),
                'path' => $path,
            ],
        ]);
    }

    public function deleteImage(Request $request)
    {
        $request->validate([
            'path' => 'required|string',
        ]);

        Storage::disk('s3')->delete($request->path);

        return response()->json([
            'success' => true,
            'message' => 'Image deleted',
        ]);
    }
}

