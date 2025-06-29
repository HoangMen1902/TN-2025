<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WarehouseDetail extends Model
{
    use SoftDeletes;

    protected $table = 'warehouse_details';

    protected $fillable = ['order_id', 'sku_id', 'warehouse_id', 'quantity'];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function sku()
    {
        return $this->belongsTo(ProductSku::class);
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }
}
