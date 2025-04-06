<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Movie;

class Package extends Model
{
    protected $fillable = [
        'name',
        'description',
        'price',
        'duration_days',
        'features'
    ];

    protected $casts = [
        'price' => 'decimal:2',
    ];

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

    public function movies()
    {
        return $this->belongsToMany(Movie::class, 'movie_package');
    }
}
