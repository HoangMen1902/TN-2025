<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductCombo extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'product_combos';

    protected $fillable = [
        'description',
        'original_price',
        'sale_price',
        'quantity',
        'expired_at',
    ];

    protected $dates = ['expired_at'];
    
    public function comboSkus()
    {
        return $this->hasMany(ComboSku::class, 'combo_id');
    }
    
}
