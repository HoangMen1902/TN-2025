<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductEbook extends Model
{
    use SoftDeletes;
    protected $table = 'product_ebooks';

    protected $fillable = [
        'title',
        'description',
        'author',
        'cover_image',
        'price',
        'file_path',
        'product_id',
        'ebook_status',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'ebook_user', 'ebook_id', 'user_id');
    }

    public function readProgress()
    {
        return $this->hasMany(EbookReadProgress::class, 'ebook_id');
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'category_ebook', 'ebook_id', 'category_id');
    }

    public function chapters()
    {
        return $this->hasMany(EbookChapter::class, 'ebook_id');
    }
    public function tags()
    {
        return $this->belongsToMany(RelatedTag::class, 'ebook_tag', 'ebook_id', 'tag_id');
    }
}