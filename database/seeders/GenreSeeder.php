<?php

namespace Database\Seeders;

use App\Models\Genre;
use Illuminate\Database\Seeder;

class GenreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $genres = [
            ['name' => 'Action', 'description' => 'High-energy stories with combat and adventure'],
            ['name' => 'Romance', 'description' => 'Love stories and romantic relationships'],
            ['name' => 'Fantasy', 'description' => 'Magical worlds and supernatural elements'],
            ['name' => 'Drama', 'description' => 'Emotional and character-driven stories'],
            ['name' => 'Comedy', 'description' => 'Humorous and light-hearted stories'],
            ['name' => 'School Life', 'description' => 'Stories set in educational environments'],
            ['name' => 'Slice of Life', 'description' => 'Realistic everyday life situations'],
            ['name' => 'Supernatural', 'description' => 'Stories involving supernatural phenomena'],
            ['name' => 'Martial Arts', 'description' => 'Combat and martial arts focused stories'],
            ['name' => 'Historical', 'description' => 'Stories set in historical time periods'],
            ['name' => 'Mystery', 'description' => 'Suspenseful stories with puzzles to solve'],
            ['name' => 'Horror', 'description' => 'Scary and frightening stories'],
            ['name' => 'Psychological', 'description' => 'Mind-bending and psychological stories'],
            ['name' => 'Sci-Fi', 'description' => 'Science fiction and futuristic stories'],
            ['name' => 'Adventure', 'description' => 'Journey and exploration stories'],
        ];

        foreach ($genres as $genre) {
            Genre::create($genre);
        }
    }
}
