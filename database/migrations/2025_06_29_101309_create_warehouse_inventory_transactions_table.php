<?php

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
        Schema::create('warehouse_inventory_transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('warehouse_id');
            $table->unsignedBigInteger('sku_id');
            $table->unsignedBigInteger('location_id');
            $table->enum('transaction_type', ['IN', 'OUT', 'MOVE']);
            $table->integer('transaction_qty');
            $table->unsignedBigInteger('order_id')->nullable();
            $table->unsignedBigInteger('transfer_id')->nullable(); // nếu là MOVE từ chuyển kho
            $table->text('notes')->nullable();
            $table->timestamp('transaction_date')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('warehouse_id')->references('id')->on('warehouses')->onDelete('cascade');
            $table->foreign('sku_id')->references('id')->on('product_skus')->onDelete('cascade');
            $table->foreign('location_id')->references('id')->on('warehouse_locations')->onDelete('cascade');
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('set null');
            $table->foreign('transfer_id')->references('id')->on('warehouse_transfers')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('warehouse_inventory_transactions');
    }
};
