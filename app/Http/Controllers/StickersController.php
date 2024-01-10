<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class StickersController extends Controller
{
    public function getImageCategories()
    {
        $basePath = storage_path('app/public/uploads');
    
        if (!is_dir($basePath)) {
            // Handle the case where the 'uploads' directory doesn't exist
            return response()->json(['error' => 'Uploads directory not found']);
        }
    
        $categories = [];
        $links = [];
    
        // Get all subdirectories in the 'uploads' directory
        $subdirectories = array_diff(scandir($basePath), ['.', '..']);
    
        foreach ($subdirectories as $category) {
            $categoryPath = $basePath . DIRECTORY_SEPARATOR . $category;
    
            // Check if it's a directory
            if (is_dir($categoryPath)) {
                $categories[] = $category;
    
                // Get all files in the category directory
                $files = Storage::files('public/uploads/' . $category);
    
                // Generate links for each file
          
                $categoryLinks = array_map(function ($file) use ($category) { // Added 'use ($category)'
                    // Extract the file name and append it to the 'storage' path
                    $fileName = basename($file);
                    return asset('storage/uploads/' . $category . '/' . $fileName);
                }, $files);

                $links[$category] = $categoryLinks;

            }
        }
    
        return response()->json(['categories' => $categories, 'links' => $links]);
    }
    


}
