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
        Schema::create('flashsale_products', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sku_id');
            $table->unsignedBigInteger('flashsale_id');
            $table->foreign('sku_id')->references('id')->on('product_skus');
            $table->foreign('flashsale_id')->references('id')->on('flashsales')->onDelete('cascade');
            $table->timestamps();

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
