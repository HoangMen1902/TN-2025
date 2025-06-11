<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FlashsaleProduct extends Model
{
    use HasFactory;

    protected $table = 'flashsale_products';

    protected $fillable = [
        'product_id',
        'flashsale_id',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function flashsale()
{
    return $this->belongsTo(\App\Models\Flashsale::class);
}

public function sku()
{
    return $this->belongsTo(\App\Models\ProductSku::class);
}

}
