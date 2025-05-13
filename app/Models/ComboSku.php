<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ComboSku extends Model
{
    use HasFactory;

    protected $table = 'combo_skus';

    protected $fillable = [
        'sku_id',
        'combo_id',
        'quantity',
    ];
 
    public function sku()
    {
        return $this->belongsTo(ProductSku::class);
    }
 
    public function combo()
    {
        return $this->belongsTo(ProductCombo::class);
    }
}
