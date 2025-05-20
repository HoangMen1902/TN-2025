<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Provider extends Model
{
    protected $table = "providers";

    protected $fillable = ['provider_name', 'provider_status', 'provider_token', 'token_expired_time'];

}
