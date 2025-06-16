<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Publisher extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'publishers';

    protected $fillable = [
        'publisher_name',
        'publisher_status',
    ];

    protected $appends = ['publisher_status_bool'];

    public function getPublisherStatusBoolAttribute(): bool
    {
        return $this->publisher_status === 'active';
    }

    public function setPublisherStatusBoolAttribute($value): void
    {
        $this->publisher_status = $value ? 'active' : 'inactive';
    }


    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
