<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductSku extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'product_skus';

    protected $fillable = [
        'product_id',
        'sku',
        'images',
        'quantity',
        'price',
        'sale_price',
        'expired_at',
        'ISBN',
    ];

    protected $casts = [
        'images' => 'array',
    ];

    protected $dates = ['expired_at'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function skuValues() {
        return $this->hasMany(SkuValue::class, 'sku_id');
    }

    public function options() {
        return $this->belongsToMany(Option::class, 'sku_values', 'sku_id', 'option_id');
    }
    
    public function optionValues() {
        return $this->belongsToMany(OptionValue::class, 'sku_values', 'sku_id', 'value_id');
    }
}
