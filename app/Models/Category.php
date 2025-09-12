<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class Category extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'categories';

    protected $fillable = [
        'name',
        'category_status',
        'parent_id',
        'slug',
        'image',
    ];

    protected $appends = ['category_status_bool'];

    public function getCategoryStatusBoolAttribute(): bool
    {
        return $this->category_status === 'active';
    }

    public function setCategoryStatusBoolAttribute($value): void
    {
        $this->category_status = $value ? 'active' : 'inactive';
    }
 
    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    public function scopeActive($query)
    {
        return $query->where('category_status', 'active');
    }

    public function products() {
        return $this->belongsToMany(Product::class, 'product_categories');
    }

    public function isParent()
    {
        return is_null($this->parent_id);
    }

    // Tự động tạo slug từ name
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($category) {
            if (empty($category->slug)) {
                $category->slug = $category->generateUniqueSlug($category->name);
            }
        });

        static::updating(function ($category) {
            if ($category->isDirty('name') && empty($category->slug)) {
                $category->slug = $category->generateUniqueSlug($category->name);
            }
        });
    }

    public function generateUniqueSlug($name)
    {
        $slug = Str::slug($name);
        $originalSlug = $slug;
        $counter = 1;

        while (static::where('slug', $slug)->where('id', '!=', $this->id)->exists()) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }
}