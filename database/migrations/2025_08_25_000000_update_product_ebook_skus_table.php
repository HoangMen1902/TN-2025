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
        Schema::table('product_ebooks', function (Blueprint $table) {
            // Xóa product_id nếu tồn tại
            if (Schema::hasColumn('product_ebooks', 'product_id')) {
                $table->dropForeign(['product_id']);
                $table->dropColumn('product_id');
            }

            // Thêm sku_id
            $table->unsignedBigInteger('sku_id')->nullable()->after('id');

            // Thêm khóa ngoại đúng bảng product_skus
            $table->foreign('sku_id')
                ->references('id')
                ->on('product_skus')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('product_ebooks', function (Blueprint $table) {
            // Xóa ràng buộc sku_id
            $table->dropForeign(['sku_id']);
            $table->dropColumn('sku_id');

            // Thêm lại product_id nếu rollback
            $table->unsignedBigInteger('product_id')->nullable()->after('id');
            $table->foreign('product_id')
                ->references('id')
                ->on('products')
                ->onDelete('set null');
        });
    }
};
