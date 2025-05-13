<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentDetail extends Model
{
    use HasFactory;

    protected $table = 'payment_detail';

    protected $fillable = [
        'order_id',
        'payment_method',
        'payment_id',
        'tracking_id',
        'shipment_unit',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }
}
