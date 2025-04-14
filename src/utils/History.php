<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class History extends Model
{
    protected $fillable = [
        'user_id',
        'episode_id',
        'progress',
        'last_watched_at'
    ];

    protected $primaryKey = ['user_id', 'episode_id'];
    public $incrementing = false;

    protected $casts = [
        'last_watched_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function episode()
    {
        return $this->belongsTo(Episode::class);
    }
}
