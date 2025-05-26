<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class PaymentMethod extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'payment_methods';

    protected $fillable = [
        'method_name',
        'method_status',
    ];

    protected $casts = [
        'method_name' => 'string',
        'method_status' => 'string',
    ];
}
