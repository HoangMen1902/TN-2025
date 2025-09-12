<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserEbookChapterStatus extends Model
{
    protected $table = 'user_ebook_chapter_statuses';

    protected $fillable = [
        'user_id',
        'ebook_id',
        'chapter_id',
        'is_read',
        'is_favorite',
        'reading_position',
    ];

    public $timestamps = true;
    public function chapter()
    {
        return $this->belongsTo(EbookChapter::class, 'chapter_id');
    }

    public function ebook()
    {
        return $this->belongsTo(ProductEbook::class, 'ebook_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
