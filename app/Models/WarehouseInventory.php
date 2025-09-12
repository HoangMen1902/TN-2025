<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WarehouseInventory extends Model
{
    use SoftDeletes;

    protected $table = 'warehouse_inventory';

    protected $fillable = ['warehouse_id', 'sku_id', 'location_id', 'quantity_on_hand'];

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function location()
    {
        return $this->belongsTo(WarehouseLocation::class);
    }

    public function sku()
    {
        return $this->belongsTo(ProductSku::class);
    }
}
