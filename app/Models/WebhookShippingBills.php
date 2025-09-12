<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WebhookShippingBills extends Model
{
    protected $table = 'webhook_shipping_bills';
    protected $fillable = ['order_id', 'shipping_status', 'raw_response', 'current_location', 'status_name'];


    public function order() {
        return $this->belongsTo(Order::class);
    }
}
