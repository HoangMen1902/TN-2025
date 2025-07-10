<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EbookOrderDetail extends Model
{
    protected $table = 'ebook_order_details';
    protected $fillable = ['ebook_order_id', 'ebook_id', 'price', 'quantity', 'total_price'];

    public function order()
    {
        return $this->belongsTo(EbookOrder::class, 'ebook_order_id'); 
    }
}