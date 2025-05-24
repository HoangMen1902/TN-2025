<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EmailChangeOtp extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'new_email', 'otp_code', 'expires_at'
    ];

    public $timestamps = true;

    protected $dates = ['expires_at'];
}
