<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Option extends Model
{
    protected $table = 'options';
    protected $fillable = ['name', 'option_status'];

    public function skuValues() {
        return $this->hasMany(SkuValue::class);
    }

    public function optionValues() {
        return $this->hasMany(OptionValue::class);
    }

    
}
