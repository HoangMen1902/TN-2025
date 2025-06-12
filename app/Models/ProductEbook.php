<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductEbook extends Model
{
    use SoftDeletes;

    protected $table = 'product_ebooks';

    protected $fillable = [
        'product_id',
        'filetype',
        'content',
        'filepath',
        'price',
        'ebook_status',
    ];

    // Quan hệ: ebook thuộc về 1 sản phẩm
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
