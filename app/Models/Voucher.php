<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Voucher extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'vouchers';

    protected $fillable = [
        'voucher_name',
        'requirement_price',
        'reduced_amount',
        'voucher_type',
        'expired_at',
        'vouchers_status',
    ];

    protected $casts = [
        'requirement_price' => 'float',
        'reduced_amount' => 'float',
        'expired_at' => 'datetime',
    ];

    // Quan hệ với bảng trung gian VoucherUsed
    public function voucherUsed()
    {
        return $this->hasMany(VoucherUsed::class);
    }
}
