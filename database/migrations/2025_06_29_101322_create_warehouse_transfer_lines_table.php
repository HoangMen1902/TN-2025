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
        Schema::create('warehouse_transfer_lines', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('transfer_id');
            $table->unsignedBigInteger('sku_id');
            $table->unsignedBigInteger('source_location_id');
            $table->unsignedBigInteger('destination_location_id');
            $table->integer('quantity');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('transfer_id')->references('id')->on('warehouse_transfers')->onDelete('cascade');
            $table->foreign('sku_id')->references('id')->on('product_skus')->onDelete('restrict');
            $table->foreign('source_location_id')->references('id')->on('warehouse_locations')->onDelete('restrict');
            $table->foreign('destination_location_id')->references('id')->on('warehouse_locations')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('warehouse_transfer_lines');
    }
};
