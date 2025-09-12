<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Province extends Model
{
    protected $fillable = ['name', 'province_code'];

    public function districts()
    {
        return $this->hasMany(District::class);
    }

    public function provider_province() {
        return $this->hasOne(providerProvinces::class);
    }

}