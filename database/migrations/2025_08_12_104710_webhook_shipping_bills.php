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
        Schema::create('webhook_shipping_bills', function (Blueprint $table) {
            $table->string('shipping_status')->nullable();
            $table->text('status_name')->nullable();
            $table->text('current_location')->nullable();
            $table->text('raw_response')->nullable();
            $table->unsignedBigInteger('order_id');
            $table->timestamps();
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
