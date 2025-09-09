<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;

class Genre extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
    ];

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($genre) {
            if (empty($genre->slug)) {
                $genre->slug = Str::slug($genre->name);
            }
        });
    }

    public function manhwas(): BelongsToMany
    {
        return $this->belongsToMany(Manhwa::class, 'manhwa_genre');
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }
}
