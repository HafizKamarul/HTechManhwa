<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Manhwa;
use App\Models\Chapter;
use Illuminate\Support\Facades\File;

class FixChapterImageOrder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'manhwa:fix-image-order {manhwa_id?} {chapter_number?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fix image ordering for chapters by renaming files in proper sequence';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $manhwaId = $this->argument('manhwa_id');
        $chapterNumber = $this->argument('chapter_number');

        if ($manhwaId && $chapterNumber) {
            // Fix specific chapter
            $manhwa = Manhwa::find($manhwaId);
            if (!$manhwa) {
                $this->error("Manhwa with ID {$manhwaId} not found.");
                return;
            }

            $chapter = $manhwa->chapters()->where('chapter_number', $chapterNumber)->first();
            if (!$chapter) {
                $this->error("Chapter {$chapterNumber} not found for manhwa '{$manhwa->title}'.");
                return;
            }

            $this->fixChapterImages($manhwa, $chapter);
        } else {
            // Show available manhwa and let user choose
            $this->info('Available Manhwa:');
            $manhwas = Manhwa::with('chapters')->get();
            
            foreach ($manhwas as $manhwa) {
                $this->info("ID: {$manhwa->id} - {$manhwa->title} ({$manhwa->chapters->count()} chapters)");
            }

            $manhwaId = $this->ask('Enter Manhwa ID to fix');
            $manhwa = Manhwa::find($manhwaId);
            
            if (!$manhwa) {
                $this->error("Manhwa with ID {$manhwaId} not found.");
                return;
            }

            $this->info("Chapters in '{$manhwa->title}':");
            foreach ($manhwa->chapters as $chapter) {
                $this->info("Chapter {$chapter->chapter_number}: {$chapter->title}");
            }

            $chapterNumber = $this->ask('Enter Chapter number to fix (or "all" for all chapters)');
            
            if ($chapterNumber === 'all') {
                foreach ($manhwa->chapters as $chapter) {
                    $this->fixChapterImages($manhwa, $chapter);
                }
            } else {
                $chapter = $manhwa->chapters()->where('chapter_number', $chapterNumber)->first();
                if (!$chapter) {
                    $this->error("Chapter {$chapterNumber} not found.");
                    return;
                }
                $this->fixChapterImages($manhwa, $chapter);
            }
        }

        $this->info('Image ordering fix completed!');
    }

    private function fixChapterImages(Manhwa $manhwa, Chapter $chapter)
    {
        $chapterPath = storage_path("app/public/manhwa/chapters/{$chapter->folder_path}");
        
        if (!File::exists($chapterPath)) {
            $this->error("Chapter folder not found: {$chapterPath}");
            return;
        }

        // Get all image files
        $files = File::files($chapterPath);
        $imageFiles = [];
        
        foreach ($files as $file) {
            if (in_array(strtolower($file->getExtension()), ['jpg', 'jpeg', 'png', 'webp', 'gif'])) {
                $imageFiles[] = $file;
            }
        }

        if (empty($imageFiles)) {
            $this->warn("No image files found in chapter: {$chapter->title}");
            return;
        }

        // Sort files naturally by filename
        usort($imageFiles, function($a, $b) {
            return strnatcmp($a->getFilename(), $b->getFilename());
        });

        $this->info("Fixing chapter: {$chapter->title} ({$manhwa->title})");
        $this->info("Found " . count($imageFiles) . " images");

        // Show current order
        $this->info("Current file order:");
        foreach ($imageFiles as $index => $file) {
            $this->line("  " . ($index + 1) . ". " . $file->getFilename());
        }

        if (!$this->confirm('Proceed with renaming these files?')) {
            $this->info('Skipped chapter: ' . $chapter->title);
            return;
        }

        // Create temporary directory
        $tempDir = $chapterPath . '/temp_' . time();
        mkdir($tempDir, 0755, true);

        try {
            // Move files to temp directory with new names
            foreach ($imageFiles as $index => $file) {
                $pageNumber = str_pad($index + 1, 3, '0', STR_PAD_LEFT);
                $newFilename = $pageNumber . '.' . $file->getExtension();
                $tempPath = $tempDir . '/' . $newFilename;
                
                if (!rename($file->getPathname(), $tempPath)) {
                    throw new \Exception("Failed to move file: " . $file->getFilename());
                }
            }

            // Move files back to original directory
            $tempFiles = File::files($tempDir);
            foreach ($tempFiles as $file) {
                $finalPath = $chapterPath . '/' . $file->getFilename();
                if (!rename($file->getPathname(), $finalPath)) {
                    throw new \Exception("Failed to move temp file: " . $file->getFilename());
                }
            }

            // Update page count
            $chapter->update(['page_count' => count($imageFiles)]);

            // Clean up temp directory
            rmdir($tempDir);

            $this->info("✅ Successfully fixed image order for: {$chapter->title}");

        } catch (\Exception $e) {
            $this->error("❌ Error fixing chapter {$chapter->title}: " . $e->getMessage());
            
            // Try to clean up temp directory
            if (File::exists($tempDir)) {
                $tempFiles = File::files($tempDir);
                foreach ($tempFiles as $file) {
                    unlink($file->getPathname());
                }
                rmdir($tempDir);
            }
        }
    }
}
