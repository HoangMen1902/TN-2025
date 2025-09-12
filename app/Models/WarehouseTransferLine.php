<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WarehouseTransferLine extends Model
{
    use SoftDeletes;

    protected $table = 'warehouse_transfer_lines';

    protected $fillable = [
        'transfer_id', 'sku_id', 'source_location_id',
        'destination_location_id', 'quantity'
    ];

    public function transfer()
    {
        return $this->belongsTo(WarehouseTransfer::class);
    }

    public function sku()
    {
        return $this->belongsTo(ProductSku::class);
    }

    public function sourceLocation()
    {
        return $this->belongsTo(WarehouseLocation::class, 'source_location_id');
    }

    public function destinationLocation()
    {
        return $this->belongsTo(WarehouseLocation::class, 'destination_location_id');
    }
}
