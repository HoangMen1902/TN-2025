<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EbookChapter extends Model
{
    protected $fillable = [
        'ebook_id',
        'chapter_name',
        'file_path',
        'start_page',
        'end_page',
        'content',
        'is_locked',
    ];

    public function ebook()
    {
        return $this->belongsTo(ProductEbook::class, 'ebook_id');
    }
}