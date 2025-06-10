<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'orders';

    protected $fillable = [
        'orders_status',
        'user_id',
        'address',
        'is_approved',
        'phone',
        'reason',
        'contact_email',
        'customer_name',
        'total_price',
        'shipment_price'
    ];

    protected $casts = [
        'is_approved' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class);
    }
    public function getCalculatedTotalPriceAttribute()
    {
        return $this->orderDetails->sum(function ($item) {
            return $item->price * $item->quantity;
        });
    }
}
