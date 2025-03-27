<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Favorite extends Model
{
    protected $fillable = [
        'user_id',
        'movie_id',
        'added_at'
    ];

    protected $primaryKey = ['user_id', 'movie_id'];
    public $incrementing = false;

    protected $casts = [
        'added_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function movie()
    {
        return $this->belongsTo(Movie::class);
    }
}
