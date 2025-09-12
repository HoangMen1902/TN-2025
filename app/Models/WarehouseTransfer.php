<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WarehouseTransfer extends Model
{
    use SoftDeletes;

    protected $table = 'warehouse_transfers';

    protected $fillable = [
        'from_warehouse_id', 'to_warehouse_id', 'transfer_date',
        'status', 'created_by', 'completed_at', 'notes'
    ];

    public function fromWarehouse()
    {
        return $this->belongsTo(Warehouse::class, 'from_warehouse_id');
    }

    public function toWarehouse()
    {
        return $this->belongsTo(Warehouse::class, 'to_warehouse_id');
    }

    public function lines()
    {
        return $this->hasMany(WarehouseTransferLine::class, 'transfer_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
