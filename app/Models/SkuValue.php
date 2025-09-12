<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SkuValue extends Model
{
    use HasFactory;

    protected $table = 'sku_values';

    protected $fillable = [
        'sku_id',
        'option_id',
        'value_id',
    ];
    public function sku()
    {
        return $this->belongsTo(ProductSku::class, 'sku_id');
    }

    public function option()
    {
        return $this->belongsTo(Option::class, 'option_id');
    }

    public function value()
    {
        return $this->belongsTo(OptionValue::class, 'value_id');
    }
}
