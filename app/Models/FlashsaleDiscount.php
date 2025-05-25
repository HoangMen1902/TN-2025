<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FlashsaleDiscount extends Model
{
    use SoftDeletes;

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
}
