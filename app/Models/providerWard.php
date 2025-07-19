<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class providerWard extends Model
{
    protected $table = 'provider_wards';
    protected $fillable = ['provider_id', 'ward_id', 'provider_ward_code', 'provider_ward_name', 'provider_district_code'];
}
