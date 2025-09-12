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
        Schema::table('order_details', function (Blueprint $table) {
            $table->dropForeign(['sku_id']);
        });

        Schema::table('order_details', function (Blueprint $table) {
            $table->unsignedBigInteger('sku_id')->nullable()->change();

            $table->foreign('sku_id')
                ->references('id')->on('product_skus')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('order_details', function (Blueprint $table) {
            //
        });
    }
};
