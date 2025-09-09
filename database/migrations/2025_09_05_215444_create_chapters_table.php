<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('chapters', function (Blueprint $table) {
            $table->id();
            $table->foreignId('manhwa_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->string('slug');
            $table->integer('chapter_number');
            $table->string('folder_path'); // path to folder containing chapter images
            $table->integer('page_count')->default(0);
            $table->integer('views')->default(0);
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
            
            $table->unique(['manhwa_id', 'chapter_number']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chapters');
    }
};
