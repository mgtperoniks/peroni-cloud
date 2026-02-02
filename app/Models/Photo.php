<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Photo extends Model
{
    protected $fillable = [
        'user_id',
        'filename',
        'thumbnail',
        'photo_date',
        'location',
        'department',
        'notes',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
