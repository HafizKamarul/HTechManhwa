<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;

class Manhwa extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'description',
        'author',
        'artist',
        'status',
        'year',
        'cover_image',
        'views',
        'rating',
        'rating_count',
    ];

    protected $casts = [
        'year' => 'integer',
        'views' => 'integer',
        'rating' => 'decimal:2',
        'rating_count' => 'integer',
    ];

    // Automatically generate slug from title
    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($manhwa) {
            if (empty($manhwa->slug)) {
                $manhwa->slug = Str::slug($manhwa->title);
            }
        });
    }

    public function chapters(): HasMany
    {
        return $this->hasMany(Chapter::class)->orderBy('chapter_number');
    }

    public function genres(): BelongsToMany
    {
        return $this->belongsToMany(Genre::class, 'manhwa_genre');
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }
}
