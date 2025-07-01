<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    use HasFactory;

    protected $table = 'order_details';

    protected $fillable = [
        'order_id',
        'sku_id',
        'price',
        'quantity',
        'combo_id'
    ];



    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    public function sku()
    {
        return $this->belongsTo(ProductSku::class, 'sku_id');
    }

    public function combo()
    {
        return $this->belongsTo(ProductCombo::class, 'combo_id'); 
    }
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
