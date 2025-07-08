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
        'voucher_id',
    ];
    protected $casts = [
        'won_at' => 'datetime',
    ];
    public function prize()
    {
        return $this->belongsTo(Prize::class, 'prize_id');
    }
    public function voucher()
    {
        return $this->belongsTo(Voucher::class);
    }
}
