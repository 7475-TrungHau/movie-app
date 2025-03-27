<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Comment extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'movie_id',
        'parent_id',
        'root_id',
        'content'
    ];

    protected $dates = ['deleted_at'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function movie()
    {
        return $this->belongsTo(Movie::class);
    }

    public function parent()
    {
        return $this->belongsTo(Comment::class, 'parent_id');
    }

    public function root()
    {
        return $this->belongsTo(Comment::class, 'root_id');
    }

    public function replies()
    {
        return $this->hasMany(Comment::class, 'parent_id');
    }

    // Kiểm tra xem comment có phải root không
    public function isRoot()
    {
        return is_null($this->parent_id) && is_null($this->root_id);
    }

    // Kiểm tra xem comment có reply không
    public function hasReplies()
    {
        return $this->replies()->count() > 0;
    }
}
