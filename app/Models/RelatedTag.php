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
        'related_tags_status',
    ];
 

   
}
