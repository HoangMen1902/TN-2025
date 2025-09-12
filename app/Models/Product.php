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
        'product_status',
        'thumbnail',
        'publisher_id',
        'published_at',
        'slug',
        'length'
    ];


    public function publisher()
    {
        return $this->belongsTo(Publisher::class, 'publisher_id');
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'product_categories', 'product_id', 'category_id');
    }


    public function productSkus()
    {
        return $this->hasMany(ProductSku::class, 'product_id', 'id');
    }

    public function tags()
    {
        return $this->belongsToMany(RelatedTag::class, 'product_tags', 'product_id', 'tag_id');
    }


    public function productPreview()
    {
        return $this->hasOne(ProductPreview::class);
    }
    public function preview()
    {
        return $this->hasOne(ProductPreview::class);
    }
    public function sku()
    {
        return $this->hasOne(ProductSku::class);
    }
    public function preorders()
    {
        return $this->hasMany(\App\Models\Preorder::class);
    }
}
