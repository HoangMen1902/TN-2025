<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->enum('orders_status', ['Đang xử lý', 'Đã thanh toán', 'Vận chuyển', 'Chờ hoàn tiền' , 'Đã hoàn tiền' , 'Đã giao', 'Đã hủy'])->default('Đang xử lý');
            $table->unsignedBigInteger('user_id');
            $table->text('address');
            $table->string('phone');
            $table->boolean('is_approved')->default(false);
            $table->string('customer_name')->nullable(); // bổ sung
            $table->string('contact_email')->nullable(); // bổ sung
            $table->text('reason')->nullable(); // bổ sung
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
