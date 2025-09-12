<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('ebook_orders', function (Blueprint $table) {
            $table->id(); 
            $table->enum('orders_status', [
                'Chờ thanh toán',
                'Đang xử lý',
                'Đã thanh toán',
                'Vận chuyển',
                'Chờ hoàn tiền',
                'Đã hoàn tiền',
                'Đã giao',
                'Đã hủy'
            ])->default('Chờ thanh toán')->nullable(false);
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->decimal('total_price', 15, 2)->nullable(false);
            $table->boolean('is_approved')->default(0);
            $table->timestamps();

            $table->index('user_id', 'idx_ebook_orders_user_id');
            $table->index('orders_status', 'idx_ebook_orders_orders_status');
        });
    }

    public function down()
    {
        Schema::dropIfExists('ebook_orders');
    }
};