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
        'voucher_code',
        'voucher_name',
        'requirement_price',
        'reduced_amount',
        'voucher_type',
        'expired_at',
        'voucher_status',
        'max_discount_amount',
        'quantity',
        'usage_per_user',
        'voucher_scope',
        'start_at',
        'is_redeemable',
        'required_points',
    ];


    protected $casts = [
        'requirement_price' => 'float',
        'reduced_amount' => 'float',
        'expired_at' => 'datetime',
        'is_redeemable' => 'boolean',
        'required_points' => 'integer',
    ];

    // Quan hệ với bảng trung gian VoucherUsed
    public function voucherUsed()
    {
        return $this->hasMany(VoucherUsed::class);
    }
}
