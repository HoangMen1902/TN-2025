<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

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
    public function ebookPayments()
    {
        return $this->hasMany(EbookPaymentDetail::class, 'product_ebook_id');
    }
    public function isPurchasedBy($userId)
    {
        return DB::table('ebook_orders')
            ->join('ebook_order_details', 'ebook_orders.id', '=', 'ebook_order_details.ebook_order_id')
            ->join('ebook_payment_details', 'ebook_orders.id', '=', 'ebook_payment_details.ebook_order_id')
            ->where('ebook_orders.user_id', $userId)
            ->where('ebook_order_details.ebook_id', $this->id)
            ->where('ebook_payment_details.is_paid', true)
            ->exists();
    }

    public function publisher()
    {
        return $this->belongsTo(Publisher::class, 'publisher_id');
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
    // public function tags()
    // {
    //     return $this->belongsToMany(RelatedTag::class, 'ebook_tag', 'ebook_id', 'tag_id');
    // }
    public function tags()
    {
        return $this->belongsToMany(RelatedTag::class, 'product_tags', 'product_id', 'tag_id');
    }
    public function purchasedByUsers()
    {
        return $this->belongsToMany(User::class, 'user_ebooks', 'ebook_id', 'user_id');
    }
    public function likedByUsers()
    {
        return $this->belongsToMany(User::class, 'user_liked_ratings', 'rating_id', 'user_id');
    }
}
