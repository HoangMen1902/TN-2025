<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class providerProvinces extends Model
{
    protected $table = 'provider_provinces';

    protected $fillable = ['provider_id', 'province_id', 'provider_province_code', 'provider_province_name'];

    
    
}
