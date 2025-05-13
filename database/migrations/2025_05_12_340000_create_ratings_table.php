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
            $table->enum('payment_method', ['vnpay', 'momo', 'cod', 'bank_transfer'])->default('cod');
            $table->string('payment_id')->nullable();
            $table->string('tracking_id')->nullable();
            $table->string('shipment_unit')->nullable();
            $table->timestamps();


            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payment_detail');
    }
};