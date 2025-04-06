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
        'features',
        'is_active'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_active' => 'boolean',
        'duration_days' => 'integer',
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
