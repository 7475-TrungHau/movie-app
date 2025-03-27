<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'thumbnail_url',
    ];

    public function movies()
    {
        return $this->hasMany(Movie::class);
    }
}
