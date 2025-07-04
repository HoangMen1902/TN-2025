<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WonPrize extends Model
{
    protected $fillable = [
        'prize_id',
        'user_id',
        'username',
        'won_at',
    ];
    protected $casts = [
    'won_at' => 'datetime',
];
    public function prize()
    {
        return $this->belongsTo(Prize::class, 'prize_id');
    }
}
