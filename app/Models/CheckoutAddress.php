<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CheckoutAddress extends Model
{
    use HasFactory;

    protected $table = 'checkout_addresses';

    protected $fillable = [
        'user_id',
        'address',
        'customer_name',
        'province_id',
        'district_id',
        'ward_id',
        'phone',
        'address_type',
        'address_default'
    ];

 
    public function user()
    {
        return $this->belongsTo(User::class);
    }

 
    public function province()
    {
        return $this->belongsTo(Province::class);
    }

 
    public function district()
    {
        return $this->belongsTo(District::class);
    }
 
    public function ward()
    {
        return $this->belongsTo(Ward::class);
    }

  
    public function getFullAddressAttribute()
    {
        return "{$this->address}, {$this->ward->name}, {$this->district->name}, {$this->province->name}";
    }
}
