<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class District extends Model
{
    use HasFactory;

    protected $table = 'districts';

    protected $fillable = [
        'name',
        'district_code',
        'province_id',
    ];
 
    public function province()
    {
        return $this->belongsTo(Province::class);
    }

 
    public function wards()
    {
        return $this->hasMany(Ward::class);
    }

  
    public function scopeOfProvince($query, $provinceId)
    {
        return $query->where('province_id', $provinceId);
    }
}
