<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OptionValue extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'option_values';

    protected $fillable = [
        'option_id',
        'value_name',
        'option_values_status',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
