<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserLikedRating extends Model
{
    use HasFactory;

    protected $table = 'user_liked_ratings';

    protected $fillable = [
        'user_id',
        'rating_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function rating()
    {
        return $this->belongsTo(Rating::class);
    }
}
