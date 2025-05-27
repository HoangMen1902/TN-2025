<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductCombo extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'product_combos';
    protected $casts = [
        'images' => 'array' 
    ];

    protected $fillable = [
        'description',
        'original_price',
        'sale_price',
        'quantity',
        'expired_at',
        'combo_name',
        'slug',
        'images',
        'length',
        'width',
        'height',
        'weight'
    ];

    protected $dates = ['expired_at'];
    
    public function comboSkus()
    {
        return $this->hasMany(ComboSku::class, 'combo_id');
    }

    public function productSkus() {
        return $this->belongsToMany(ProductSku::class, 'combo_skus', 'combo_id', 'sku_id');
    }
    
}
