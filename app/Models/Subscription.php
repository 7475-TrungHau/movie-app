<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Package;

class Subscription extends Model
{
    protected $fillable = [
        'user_id',
        'package_id',
        'start_date',
        'end_date',
        'status'
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function package()
    {
        return $this->belongsTo(Package::class, 'package_id');
    }

    public function payment()
    {
        return $this->hasOne(Payment::class, 'subscription_id');
    }
}
