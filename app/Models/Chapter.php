<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Chapter extends Model
{
    protected $fillable = [
        'manhwa_id',
        'title',
        'slug',
        'chapter_number',
        'folder_path',
        'page_count',
        'views',
        'published_at',
    ];

    protected $casts = [
        'chapter_number' => 'integer',
        'page_count' => 'integer',
        'views' => 'integer',
        'published_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($chapter) {
            if (empty($chapter->slug)) {
                $chapter->slug = Str::slug($chapter->title);
            }
            if (empty($chapter->published_at)) {
                $chapter->published_at = now();
            }
        });
    }

    public function manhwa(): BelongsTo
    {
        return $this->belongsTo(Manhwa::class);
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }
}
