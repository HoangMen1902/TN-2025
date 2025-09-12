<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("ALTER TABLE orders MODIFY COLUMN orders_status ENUM(
            'Đang xử lý',
            'Chờ thanh toán',
            'Đã thanh toán',
            'Chờ duyệt',
            'Vận chuyển',
            'Chờ hoàn tiền',
            'Đã hoàn tiền',
            'Đã giao',
            'Đã hủy',
            'Chờ trả hàng',
            'Đã trả hàng'
        ) DEFAULT 'Đang xử lý'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE orders MODIFY COLUMN orders_status ENUM(
            'Đang xử lý',
            'Chờ thanh toán',
            'Đã thanh toán',
            'Chờ duyệt',
            'Vận chuyển',
            'Chờ hoàn tiền',
            'Đã hoàn tiền',
            'Đã giao',
            'Đã hủy'
        ) DEFAULT 'Đang xử lý'");
    }
};
