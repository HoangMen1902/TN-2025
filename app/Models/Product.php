<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'products';

    protected $fillable = [
        'product_released_year',
        'author',
        'book_cover',
        'weight',
        'width',
        'height',
        'pages',
        'name',
        'description',
        'short_description',
        'products_status',
        'thumbnail',
        'publisher_id',
        'published_at'
    ];

    
    public function publisher()
    {
        return $this->belongsTo(Publisher::class, 'publisher_id');
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'product_categories');
    }

    public function productSkus()
    {
        return $this->hasMany(ProductSku::class);
    }
}
