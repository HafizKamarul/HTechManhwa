<?php

namespace Database\Seeders;

use App\Models\Manhwa;
use App\Models\Genre;
use App\Models\Chapter;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class SampleManhwaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create sample manhwa
        $manhwa = Manhwa::create([
            'title' => 'Sample Manhwa',
            'description' => 'This is a sample manhwa created for testing purposes. In a real setup, you would upload your own manhwa files and cover images.',
            'author' => 'Sample Author',
            'artist' => 'Sample Artist',
            'status' => 'ongoing',
            'year' => 2024,
            'views' => 1250,
            'rating' => 4.5,
            'rating_count' => 24,
        ]);

        // Attach genres
        $actionGenre = Genre::where('name', 'Action')->first();
        $fantasyGenre = Genre::where('name', 'Fantasy')->first();
        $adventureGenre = Genre::where('name', 'Adventure')->first();
        
        if ($actionGenre && $fantasyGenre && $adventureGenre) {
            $manhwa->genres()->attach([$actionGenre->id, $fantasyGenre->id, $adventureGenre->id]);
        }

        // Create sample chapters
        for ($i = 1; $i <= 5; $i++) {
            Chapter::create([
                'manhwa_id' => $manhwa->id,
                'title' => "Chapter Title $i",
                'chapter_number' => $i,
                'folder_path' => "sample-manhwa/chapter-$i", // This folder would contain the images
                'page_count' => rand(15, 25),
                'views' => rand(100, 800),
                'published_at' => now()->subDays(rand(1, 30)),
            ]);
        }

        // Create another sample manhwa
        $manhwa2 = Manhwa::create([
            'title' => 'Romance Sample',
            'description' => 'A sample romance manhwa for testing the genre filtering and display features.',
            'author' => 'Romance Author',
            'artist' => 'Romance Artist',
            'status' => 'completed',
            'year' => 2023,
            'views' => 2500,
            'rating' => 4.8,
            'rating_count' => 48,
        ]);

        // Attach romance genres
        $romanceGenre = Genre::where('name', 'Romance')->first();
        $schoolGenre = Genre::where('name', 'School Life')->first();
        $dramaGenre = Genre::where('name', 'Drama')->first();
        
        if ($romanceGenre && $schoolGenre && $dramaGenre) {
            $manhwa2->genres()->attach([$romanceGenre->id, $schoolGenre->id, $dramaGenre->id]);
        }

        // Create chapters for second manhwa
        for ($i = 1; $i <= 8; $i++) {
            Chapter::create([
                'manhwa_id' => $manhwa2->id,
                'title' => "Romance Chapter $i",
                'chapter_number' => $i,
                'folder_path' => "romance-sample/chapter-$i",
                'page_count' => rand(18, 22),
                'views' => rand(200, 600),
                'published_at' => now()->subDays(rand(5, 60)),
            ]);
        }
    }
}
