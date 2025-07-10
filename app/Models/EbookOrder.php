<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EbookOrder extends Model
{
    protected $table = 'ebook_orders';
    protected $fillable = ['orders_status', 'user_id', 'total_price'];
    protected $casts = [
        'total_price' => 'float',
    ];

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    public function orderDetails()
    {
        return $this->hasMany(EbookOrderDetail::class);
    }

    public function paymentDetail()
    {
        return $this->hasOne(EbookPaymentDetail::class);
    }
}