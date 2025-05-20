<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class providerDistrict extends Model
{
    protected $table = "provider_districts";
    protected $fillable = ['provider_id', 'district_id', 'provider_district_code', 'provider_district_name'];
}
