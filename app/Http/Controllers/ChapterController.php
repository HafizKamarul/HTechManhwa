<?php

namespace App\Http\Controllers;

use App\Models\Manhwa;
use App\Models\Chapter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class ChapterController extends Controller
{
    /**
     * Display the specified chapter.
     */
    public function show(Manhwa $manhwa, Chapter $chapter)
    {
        // Verify the chapter belongs to the manhwa
        if ($chapter->manhwa_id !== $manhwa->id) {
            abort(404);
        }

        // Get all images from the chapter folder
        $chapterPath = storage_path("app/public/manhwa/chapters/{$chapter->folder_path}");
        $images = [];
        
        if (File::exists($chapterPath)) {
            $files = File::files($chapterPath);
            
            // Create array with filename as key and full path as value
            $imageFiles = [];
            $supportedExtensions = ['jpg', 'jpeg', 'png', 'webp', 'gif'];
            
            foreach ($files as $file) {
                $extension = strtolower($file->getExtension());
                if (in_array($extension, $supportedExtensions)) {
                    $filename = $file->getFilename();
                    $imageFiles[$filename] = asset("storage/manhwa/chapters/{$chapter->folder_path}/" . $filename);
                }
            }
            
            // Sort by filename naturally (01.jpg, 02.jpg, 03.jpg...)
            uksort($imageFiles, 'strnatcmp');
            
            // Get sorted image URLs
            $images = array_values($imageFiles);
        }

        // Get previous and next chapters
        $previousChapter = Chapter::where('manhwa_id', $manhwa->id)
            ->where('chapter_number', '<', $chapter->chapter_number)
            ->orderBy('chapter_number', 'desc')
            ->first();

        $nextChapter = Chapter::where('manhwa_id', $manhwa->id)
            ->where('chapter_number', '>', $chapter->chapter_number)
            ->orderBy('chapter_number', 'asc')
            ->first();

        // Increment view count
        $chapter->increment('views');
        
        // Update page count if not set
        if ($chapter->page_count === 0) {
            $chapter->update(['page_count' => count($images)]);
        }

        return view('chapters.show', compact('manhwa', 'chapter', 'images', 'previousChapter', 'nextChapter'));
    }
}
