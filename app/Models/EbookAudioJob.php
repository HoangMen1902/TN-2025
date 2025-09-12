<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EbookAudioJob extends Model
{
    use HasFactory;

    protected $table = 'ebook_audio_jobs';

    protected $fillable = [
        'ebook_id',
        'chapter_ids',
        'voice',
        'speed',
        'status',
        'output_path',
        'is_locked_voice',
    ];

    protected $casts = [
        'is_locked_voice' => 'boolean',
    ];

    public function ebook()
    {
        return $this->belongsTo(ProductEbook::class, 'ebook_id');
    }
}
