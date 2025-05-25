<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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

    public function skus()
    {
        return $this->belongsToMany(ProductSku::class, 'flashsale_products', 'flashsale_id', 'sku_id')
            ->withTimestamps();
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'flashsale_categories', 'flashsale_id', 'category_id')
            ->withTimestamps();
    }

    public function discount()
    {
        return $this->hasOne(FlashsaleDiscount::class);
    }

    public function scopeActive($query)
    {
        return $query->where('started_at', '<=', now())
            ->where('expired_at', '>', now());
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
}
