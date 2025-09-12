<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WarehouseInventoryTransaction extends Model
{
    use SoftDeletes;

    protected $table = 'warehouse_inventory_transactions';

    protected $fillable = [
        'warehouse_id', 'sku_id', 'location_id',
        'transaction_type', 'transaction_qty', 'order_id',
        'transfer_id', 'notes', 'transaction_date'
    ];

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function sku()
    {
        return $this->belongsTo(ProductSku::class);
    }

    public function location()
    {
        return $this->belongsTo(WarehouseLocation::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function transfer()
    {
        return $this->belongsTo(WarehouseTransfer::class);
    }
}

