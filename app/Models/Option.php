<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Option extends Model
{
    use HasFactory;

    protected $table = 'options';

    protected $fillable = [
        'name',
        'option_status',
    ];

 
    public function values()
    {
        return $this->hasMany(OptionValue::class);
    }
}
