<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Package; // Thêm use

class Movie extends Model
{
    protected $fillable = [
        'slug',
        'name',
        'description',
        'origin_name',
        'actor',
        'director',
        'year',
        'poster_url',
        'trailer_url',
        'type',
        'thumbnail_url',
        'genres',
        'category_id',
        'rating',
        'view',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function packages()
    {
        return $this->belongsToMany(Package::class, 'movie_package');
    }

    public function avgRating()
    {
        return $this->hasOne(Rating::class)->selectRaw('avg(rating_value) as rating')->groupBy('movie_id');
    }

    public function episodes()
    {
        return $this->hasMany(Episode::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function ratings()
    {
        return $this->hasMany(Rating::class, 'movie_id', 'id');
    }

    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }
}
