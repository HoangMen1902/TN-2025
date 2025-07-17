<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class EbookPaymentDetail extends Model
{
    protected $table = 'ebook_payment_details';
    protected $fillable = ['ebook_order_id', 'payment_method', 'payment_id', 'tracking_id','is_paid'];

    public function order()
    {
        return $this->belongsTo(EbookOrder::class, 'ebook_order_id');
    }

    public function ebook()
    {
        return $this->belongsTo(ProductEbook::class, 'ebook_id');
    }
}