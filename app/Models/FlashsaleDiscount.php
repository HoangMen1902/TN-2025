<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FlashsaleDiscount extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'flashsale_discounts';

    protected $fillable = [
        'discount_type',
        'discount_amount',
        'flashsale_id',
    ];
 
    public function flashsale()
    {
        return $this->belongsTo(Flashsale::class);
    }
 
    public function applyDiscount(float $originalPrice): float
    {
        if ($this->discount_type === 'percent') {
            return max(0, $originalPrice * (1 - $this->discount_amount / 100));
        }

        if ($this->discount_type === 'specific') {
            return max(0, $originalPrice - $this->discount_amount);
        }

        return $originalPrice;
    }
}
