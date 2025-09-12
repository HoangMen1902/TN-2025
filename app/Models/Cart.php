<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $table = 'carts';

    protected $fillable = [
        'session_id',
        'user_id',
        'sku_id',
        'combo_id',
        'quantity',
        'item_type',
    ];


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function sku()
    {
        return $this->belongsTo(ProductSku::class);
    }

    public function combo()
    {
        return $this->belongsTo(ProductCombo::class);
    }

    public function scopeSkuItems($query)
    {
        return $query->where('item_type', 'sku');
    }

    public function scopeComboItems($query)
    {
        return $query->where('item_type', 'combo');
    }
}
