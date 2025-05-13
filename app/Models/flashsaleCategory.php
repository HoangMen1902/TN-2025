<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FlashsaleCategory extends Model
{
    use HasFactory;

    protected $table = 'flashsale_categories';

    protected $fillable = [
        'category_id',
        'flashsale_id',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function flashsale()
    {
        return $this->belongsTo(Flashsale::class);
    }
}
