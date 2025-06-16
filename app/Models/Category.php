<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'categories';

    protected $fillable = [
        'name',
        'category_status',
        'parent_id',
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
}
