<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class EbookReadProgress extends Model
{
    protected $fillable = ['user_id', 'ebook_id', 'last_page'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function ebook()
    {
        return $this->belongsTo(ProductEbook::class);
    }
}
