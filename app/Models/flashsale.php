<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Carbon\Carbon;

class Flashsale extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'flashsales';

    protected $fillable = [
        'name',
        'started_at',
        'expired_at',
    ];

    protected $dates = [
        'started_at',
        'expired_at',
    ];
 
    public function products()
    {
        return $this->belongsToMany(Product::class, 'flashsale_products')
                    ->withPivot('discount_price')
                    ->withTimestamps();
    }
    public function discount()
    {
        return $this->hasOne(FlashsaleDiscount::class);
    }
 
    public function isStarted(): bool
    {
        return now()->gte($this->started_at);
    }
 
    public function isExpired(): bool
    {
        return now()->gt($this->expired_at);
    }

 
    public function isActive(): bool
    {
        return $this->isStarted() && !$this->isExpired();
    }

 
    public function scopeActive($query)
    {
        return $query->where('started_at', '<=', now())
                     ->where('expired_at', '>', now());
    }
    
    public function skus()
    {
        return $this->belongsToMany(ProductSku::class, 'flashsale_products', 'flashsale_id', 'sku_id');
    }
    public function categories()
    {
        return $this->belongsToMany(Category::class, 'flashsale_categories', 'flashsale_id', 'category_id');
    }
    
}
