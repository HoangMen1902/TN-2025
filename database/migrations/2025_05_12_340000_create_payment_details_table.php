<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('payment_detail', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id');
            $table->enum('payment_method', ['payos', 'vnpay', 'momo', 'cod', 'bank_transfer', 'international'])->default('cod');
            $table->string('payment_id')->nullable();
            $table->string('tracking_id')->nullable();
            $table->string('shipment_unit')->nullable();
            
            
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payment_detail');
    }
};