<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductPreview extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'product_previews';

    protected $fillable = [
        'file_path',
        'format',
        'file_name',
        'file_size',
        'is_active',
        'product_id',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
