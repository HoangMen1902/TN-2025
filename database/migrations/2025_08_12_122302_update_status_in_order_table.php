<?php

use App\Enums\OrderStatusEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->enum('orders_status', OrderStatusEnum::getValues())
                ->default(OrderStatusEnum::DangXuLy)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->enum('orders_status', ['Đang xử lý', 'Chờ thanh toán', 'Đã thanh toán','Chờ duyệt', 'Vận chuyển', 'Chờ hoàn tiền' , 'Đã hoàn tiền' , 'Đã giao', 'Đã hủy'])->default('Đang xử lý')->change();
        });
    }
};
