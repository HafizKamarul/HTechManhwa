<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Manhwa;
use App\Models\Chapter;
use App\Models\Genre;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use ZipArchive;

class AdminController extends Controller
{
    public function index()
    {
        $manhwas = Manhwa::with('chapters', 'genres')->orderBy('created_at', 'desc')->get();
        
        return view('admin.index', compact('manhwas'));
    }

    public function createManhwa()
    {
        $genres = Genre::orderBy('name')->get();
        return view('admin.create-manhwa', compact('genres'));
    }

    public function storeManhwa(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'author' => 'nullable|string|max:255',
            'artist' => 'nullable|string|max:255',
            'status' => 'required|in:ongoing,completed,hiatus,dropped',
            'year' => 'nullable|integer|min:1900|max:' . date('Y'),
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120', // 5MB max
            'genres' => 'array',
            'genres.*' => 'exists:genres,id'
        ]);

        $manhwa = new Manhwa();
        $manhwa->title = $request->title;
        $manhwa->slug = Str::slug($request->title);
        $manhwa->description = $request->description;
        $manhwa->author = $request->author;
        $manhwa->artist = $request->artist;
        $manhwa->status = $request->status;
        $manhwa->year = $request->year;
        $manhwa->views = 0;
        $manhwa->rating = 0;
        $manhwa->rating_count = 0;

        // Handle cover image upload
        if ($request->hasFile('cover_image')) {
            $coverImage = $request->file('cover_image');
            $filename = $manhwa->slug . '-cover.' . $coverImage->getClientOriginalExtension();
            $path = $coverImage->storeAs('manhwa/covers', $filename, 'public');
            $manhwa->cover_image = $path;
        }

        $manhwa->save();

        // Attach genres
        if ($request->has('genres')) {
            $manhwa->genres()->attach($request->genres);
        }

        return redirect()->route('admin.index')->with('success', 'Manhwa created successfully!');
    }

    public function createChapter(Manhwa $manhwa)
    {
        return view('admin.create-chapter', compact('manhwa'));
    }

    public function storeChapter(Request $request, Manhwa $manhwa)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'chapter_number' => 'required|numeric',
            'chapter_images' => 'required',
            'chapter_images.*' => 'image|mimes:jpeg,png,jpg,webp|max:10240', // 10MB per image
        ]);

        // Check if chapter number already exists
        $existingChapter = $manhwa->chapters()->where('chapter_number', $request->chapter_number)->first();
        if ($existingChapter) {
            return back()->withErrors(['chapter_number' => 'Chapter number already exists for this manhwa.']);
        }

        $chapter = new Chapter();
        $chapter->manhwa_id = $manhwa->id;
        $chapter->title = $request->title;
        $chapter->chapter_number = $request->chapter_number;
        $chapter->views = 0;
        $chapter->published_at = now();

        // Create folder path for chapter images
        $folderPath = $manhwa->slug . '/chapter-' . $request->chapter_number;
        $chapter->folder_path = $folderPath;

        // Handle chapter images upload
        if ($request->hasFile('chapter_images')) {
            $images = $request->file('chapter_images');
            $pageCount = 0;

            // Create directory if it doesn't exist
            $fullPath = storage_path('app/public/manhwa/chapters/' . $folderPath);
            if (!file_exists($fullPath)) {
                mkdir($fullPath, 0755, true);
            }

            // Sort images by original name to maintain order
            $sortedImages = [];
            foreach ($images as $index => $image) {
                $sortedImages[$image->getClientOriginalName()] = $image;
            }
            ksort($sortedImages, SORT_NATURAL);

            $pageIndex = 0;
            foreach ($sortedImages as $image) {
                $pageIndex++;
                $pageNumber = str_pad($pageIndex, 3, '0', STR_PAD_LEFT);
                $filename = $pageNumber . '.' . $image->getClientOriginalExtension();
                
                // Use move instead of storeAs to preserve original quality
                $destinationPath = $fullPath . '/' . $filename;
                if ($image->move($fullPath, $filename)) {
                    $pageCount++;
                } else {
                    \Log::warning("Failed to move image: " . $image->getClientOriginalName());
                }
            }

            $chapter->page_count = $pageCount;
        }

        $chapter->save();

        return redirect()->route('admin.index')->with('success', 'Chapter added successfully!');
    }

    public function uploadChapterZip(Request $request, Manhwa $manhwa)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'chapter_number' => 'required|numeric',
            'chapter_zip' => 'required|file|mimes:zip|max:102400', // 100MB max
        ]);

        // Check if chapter number already exists
        $existingChapter = $manhwa->chapters()->where('chapter_number', $request->chapter_number)->first();
        if ($existingChapter) {
            return back()->withErrors(['chapter_number' => 'Chapter number already exists for this manhwa.']);
        }

        try {
            $zipFile = $request->file('chapter_zip');
            
            // Validate uploaded file
            if (!$zipFile || !$zipFile->isValid()) {
                \Log::warning('ZIP upload: Invalid file uploaded', [
                    'hasFile' => $zipFile !== null,
                    'isValid' => $zipFile ? $zipFile->isValid() : false,
                    'error' => $zipFile ? $zipFile->getError() : 'No file'
                ]);
                return back()->withErrors(['chapter_zip' => 'The uploaded file is invalid or corrupted.']);
            }

            // Get original filename and create safe temp name
            $originalName = $zipFile->getClientOriginalName();
            $tempFileName = time() . '_' . uniqid() . '.zip';
            
            // Ensure temp directory exists
            $tempDir = storage_path('app/temp');
            if (!file_exists($tempDir)) {
                mkdir($tempDir, 0755, true);
            }

            // Use direct file operations instead of Laravel Storage for better control
            $tempPath = $tempDir . DIRECTORY_SEPARATOR . $tempFileName;
            
            // Try Laravel's move method first, then fallback to copy + unlink
            try {
                $uploadSuccess = $zipFile->move($tempDir, $tempFileName);
            } catch (\Exception $e) {
                \Log::warning('ZIP upload: Move failed, trying fallback', ['error' => $e->getMessage()]);
                // Fallback: manual copy
                $uploadSuccess = false;
                $originalPath = $zipFile->getRealPath();
                if ($originalPath && copy($originalPath, $tempPath)) {
                    $uploadSuccess = true;
                    \Log::info('ZIP upload: Fallback copy succeeded');
                } else {
                    \Log::error('ZIP upload: Both move and copy failed', [
                        'originalPath' => $originalPath,
                        'tempPath' => $tempPath,
                        'tempDirWritable' => is_writable($tempDir)
                    ]);
                }
            }
            
            if (!$uploadSuccess || !file_exists($tempPath)) {
                \Log::error('ZIP upload: File save failed', [
                    'uploadSuccess' => $uploadSuccess,
                    'fileExists' => file_exists($tempPath),
                    'tempPath' => $tempPath,
                    'tempDir' => $tempDir,
                    'tempDirExists' => file_exists($tempDir),
                    'tempDirWritable' => is_writable($tempDir)
                ]);
                return back()->withErrors(['chapter_zip' => 'ZIP file was not saved properly. Check storage permissions or try a smaller file.']);
            }
            
            
            // Comprehensive file validation
            if (!file_exists($tempPath)) {
                return back()->withErrors(['chapter_zip' => 'ZIP file was not saved properly.']);
            }
            
            if (!is_readable($tempPath)) {
                unlink($tempPath);
                return back()->withErrors(['chapter_zip' => 'ZIP file is not readable. Check file permissions.']);
            }
            
            $fileSize = filesize($tempPath);
            if ($fileSize === false || $fileSize === 0) {
                unlink($tempPath);
                return back()->withErrors(['chapter_zip' => 'ZIP file is empty or corrupted (size: ' . ($fileSize === false ? 'unknown' : '0') . ').']);
            }

            // Initialize ZipArchive
            $zip = new ZipArchive;
            $extractPath = storage_path('app/temp/extract_' . time() . '_' . uniqid());

            // Create extraction directory
            if (!mkdir($extractPath, 0755, true)) {
                Storage::delete($tempPath);
                return back()->withErrors(['chapter_zip' => 'Failed to create extraction directory. Check permissions.']);
            }

            // Attempt to open ZIP
            $zipResult = $zip->open($tempPath);
            
            if ($zipResult !== TRUE) {
                unlink($tempPath);
                $this->deleteDirectory($extractPath);
                
                $errorMessage = $this->getZipErrorMessage($zipResult, $tempPath, $fileSize);
                return back()->withErrors(['chapter_zip' => $errorMessage]);
            }

            // Check ZIP contents
            $numFiles = $zip->numFiles;
            if ($numFiles === 0) {
                $zip->close();
                unlink($tempPath);
                $this->deleteDirectory($extractPath);
                return back()->withErrors(['chapter_zip' => 'ZIP file contains no files.']);
            }

            // Extract files
            $extractSuccess = $zip->extractTo($extractPath);
            $zip->close();

            if (!$extractSuccess) {
                unlink($tempPath);
                $this->deleteDirectory($extractPath);
                return back()->withErrors(['chapter_zip' => 'Failed to extract ZIP contents. The archive may be corrupted.']);
            }

            // Scan for image files
            $imageFiles = [];
            $allowedExtensions = ['jpg', 'jpeg', 'png', 'webp'];
            $this->scanForImages($extractPath, $imageFiles, $allowedExtensions);

            if (empty($imageFiles)) {
                unlink($tempPath);
                $this->deleteDirectory($extractPath);
                return back()->withErrors(['chapter_zip' => 'No valid image files (JPG, JPEG, PNG, WebP) found in ZIP archive.']);
            }

            // Sort files naturally by filename
            usort($imageFiles, function($a, $b) {
                return strnatcmp(basename($a), basename($b));
            });

            // Create chapter record
            $chapter = new Chapter();
            $chapter->manhwa_id = $manhwa->id;
            $chapter->title = $request->title;
            $chapter->chapter_number = $request->chapter_number;
            $chapter->views = 0;
            $chapter->published_at = now();

            // Create folder path
            $folderPath = $manhwa->slug . '/chapter-' . $request->chapter_number;
            $chapter->folder_path = $folderPath;

            // Create chapter directory
            $chapterPath = storage_path('app/public/manhwa/chapters/' . $folderPath);
            if (!file_exists($chapterPath)) {
                mkdir($chapterPath, 0755, true);
            }

            // Copy and rename images with quality preservation
            $pageCount = 0;
            foreach ($imageFiles as $index => $imagePath) {
                $pageNumber = str_pad($index + 1, 3, '0', STR_PAD_LEFT);
                $extension = strtolower(pathinfo($imagePath, PATHINFO_EXTENSION));
                $newFilename = $pageNumber . '.' . $extension;
                $newPath = $chapterPath . '/' . $newFilename;

                // Use file_get_contents + file_put_contents to preserve exact binary data
                $imageData = file_get_contents($imagePath);
                if ($imageData !== false && file_put_contents($newPath, $imageData) !== false) {
                    $pageCount++;
                    \Log::info("Successfully copied image: $imagePath -> $newPath");
                } else {
                    \Log::warning("Failed to copy image: $imagePath to $newPath");
                }
            }

            if ($pageCount === 0) {
                unlink($tempPath);
                $this->deleteDirectory($extractPath);
                return back()->withErrors(['chapter_zip' => 'Failed to copy any images from ZIP archive.']);
            }

            $chapter->page_count = $pageCount;
            $chapter->save();

            // Clean up
            unlink($tempPath);
            $this->deleteDirectory($extractPath);

            return redirect()->route('admin.index')->with('success', "Chapter uploaded successfully! $pageCount pages processed from ZIP file.");

        } catch (\Exception $e) {
            // Clean up on any exception
            if (isset($tempPath) && file_exists($tempPath)) {
                unlink($tempPath);
            }
            if (isset($extractPath)) {
                $this->deleteDirectory($extractPath);
            }
            
            \Log::error('ZIP upload exception: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return back()->withErrors(['chapter_zip' => 'Upload failed: ' . $e->getMessage()]);
        }
    }

    private function scanForImages($directory, &$imageFiles, $allowedExtensions)
    {
        try {
            $iterator = new \RecursiveIteratorIterator(
                new \RecursiveDirectoryIterator($directory, \RecursiveDirectoryIterator::SKIP_DOTS),
                \RecursiveIteratorIterator::LEAVES_ONLY
            );
            
            foreach ($iterator as $file) {
                if ($file->isFile()) {
                    $extension = strtolower($file->getExtension());
                    if (in_array($extension, $allowedExtensions)) {
                        $imageFiles[] = $file->getPathname();
                    }
                }
            }
        } catch (\Exception $e) {
            // Fallback to simple directory scan
            $files = scandir($directory);
            foreach ($files as $file) {
                if ($file === '.' || $file === '..') continue;
                
                $fullPath = $directory . DIRECTORY_SEPARATOR . $file;
                if (is_file($fullPath)) {
                    $extension = strtolower(pathinfo($file, PATHINFO_EXTENSION));
                    if (in_array($extension, $allowedExtensions)) {
                        $imageFiles[] = $fullPath;
                    }
                }
            }
        }
    }

    private function deleteDirectory($dir) {
        if (!file_exists($dir) || !is_dir($dir)) return;
        
        try {
            $files = array_diff(scandir($dir), array('.','..'));
            foreach ($files as $file) {
                $path = $dir . DIRECTORY_SEPARATOR . $file;
                if (is_dir($path)) {
                    $this->deleteDirectory($path);
                } else {
                    unlink($path);
                }
            }
            rmdir($dir);
        } catch (\Exception $e) {
            // Log error but don't fail the entire operation
            \Log::warning('Failed to delete directory: ' . $dir . ' - ' . $e->getMessage());
        }
    }

    public function deleteManhwa(Manhwa $manhwa)
    {
        // Delete cover image
        if ($manhwa->cover_image) {
            Storage::disk('public')->delete($manhwa->cover_image);
        }

        // Delete all chapter folders and images
        foreach ($manhwa->chapters as $chapter) {
            $chapterPath = 'manhwa/chapters/' . $chapter->folder_path;
            Storage::disk('public')->deleteDirectory($chapterPath);
        }

        // Delete manhwa and related data (cascade will handle chapters and genres)
        $manhwa->delete();

        return redirect()->route('admin.index')->with('success', 'Manhwa deleted successfully!');
    }

    public function deleteChapter(Manhwa $manhwa, Chapter $chapter)
    {
        // Delete chapter images folder
        $chapterPath = 'manhwa/chapters/' . $chapter->folder_path;
        Storage::disk('public')->deleteDirectory($chapterPath);

        // Delete chapter record
        $chapter->delete();

        return redirect()->route('admin.index')->with('success', 'Chapter deleted successfully!');
    }

    private function getZipErrorMessage($zipResult, $filePath, $fileSize)
    {
        $baseMessage = 'Failed to open ZIP file. ';
        
        switch($zipResult) {
            case ZipArchive::ER_NOZIP:
                $baseMessage .= 'File is not a valid ZIP archive.';
                break;
            case ZipArchive::ER_INCONS:
                $baseMessage .= 'ZIP archive is inconsistent or corrupted.';
                break;
            case ZipArchive::ER_CRC:
                $baseMessage .= 'CRC error - ZIP file is corrupted.';
                break;
            case ZipArchive::ER_READ:
                $baseMessage .= 'Read error - cannot read ZIP file.';
                break;
            case ZipArchive::ER_NOENT:
                $baseMessage .= 'File not found.';
                break;
            case ZipArchive::ER_OPEN:
                $baseMessage .= 'Cannot open file - may be locked or in use.';
                break;
            case ZipArchive::ER_SEEK:
                $baseMessage .= 'Seek error in ZIP file.';
                break;
            case ZipArchive::ER_MEMORY:
                $baseMessage .= 'Memory allocation failure.';
                break;
            default:
                $baseMessage .= 'Unknown error (code: ' . $zipResult . ').';
                break;
        }
        
        // Add diagnostic info
        $diagnostics = sprintf(
            ' [File size: %s bytes, Readable: %s]',
            $fileSize,
            is_readable($filePath) ? 'Yes' : 'No'
        );
        
        \Log::error('ZIP Error: ' . $baseMessage . $diagnostics . ' Path: ' . $filePath);
        
        return $baseMessage . ' Please try uploading the ZIP file again or check if it\'s corrupted.';
    }
}
