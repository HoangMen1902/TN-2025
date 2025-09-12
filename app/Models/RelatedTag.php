<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RelatedTag extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'related_tags';

    protected $fillable = [
        'tag_name',
        'related_tag_status',
    ];

    protected $appends = ['related_tag_status_bool'];

    public function getRelatedTagStatusBoolAttribute(): bool
    {
        return $this->related_tag_status === 'active';
    }

    public function setRelatedTagStatusBoolAttribute($value): void
    {
        $this->related_tag_status = $value ? 'active' : 'inactive';
    }
    public function productTags()
    {
        return $this->hasMany(ProductTag::class, 'tag_id');
    }

    public function products()
    {
        return $this->hasManyThrough(Product::class, ProductTag::class, 'tag_id', 'id', 'id', 'product_id');
    }
    public function ebooks()
    {
        return $this->belongsToMany(ProductEbook::class, 'ebook_tag', 'tag_id', 'ebook_id');
    }
}
