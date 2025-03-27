<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Episode extends Model
{
    protected $fillable = [
        'movie_id',
        'title',
        'description',
        'video_url',
        'release_date',
        'episode_number',
        'slug',
        'thumbnail_url'
    ];

    public function movie()
    {
        return $this->belongsTo(Movie::class);
    }
}
