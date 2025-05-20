<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VoucherUsed extends Model
{
    use HasFactory;

    protected $table = 'voucher_used';

    protected $fillable = [
        'voucher_id',
        'user_id', 
        'used_at',
    ];

    protected $casts = [
        'used_at' => 'datetime',
    ];

      public function voucher()
    {
        return $this->belongsTo(Voucher::class);
    }

  
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}