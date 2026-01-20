<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImageUploadController extends Controller
{
    public function upload(Request $request): JsonResponse
    {
        $request->validate([
            'image' => 'required|image|max:2048',
            'type' => 'required|string|in:categories,products,general',
            'name' => 'nullable|string'
        ]);

        try {
            $file = $request->file('image');
            
            // Create organized folder structure
            $folderPath = sprintf(
                '%s/%s/%s/%s',
                $request->type,
                now()->format('Y'),
                now()->format('m'),
                now()->format('d')
            );

            // Generate SEO-friendly filename
            $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $extension = $file->getClientOriginalExtension();
            $customName = $request->name ? Str::slug($request->name) : Str::slug($originalName);
            $filename = sprintf('%s-%s.%s', $customName, now()->timestamp, $extension);

            // Store the image
            $path = $file->storeAs($folderPath, $filename, 'public');
            
            return response()->json([
                'success' => true,
                'path' => $path,
                'url' => Storage::disk('public')->url($path),
                'filename' => $filename
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
