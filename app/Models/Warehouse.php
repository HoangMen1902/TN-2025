<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Warehouse extends Model
{
    use SoftDeletes;

    protected $table = 'warehouses';

    protected $fillable = ['name', 'address', 'is_active'];

    public function locations()
    {
        return $this->hasMany(WarehouseLocation::class);
    }

    public function transfersFrom()
    {
        return $this->hasMany(WarehouseTransfer::class, 'from_warehouse_id');
    }

    public function transfersTo()
    {
        return $this->hasMany(WarehouseTransfer::class, 'to_warehouse_id');
    }
}

