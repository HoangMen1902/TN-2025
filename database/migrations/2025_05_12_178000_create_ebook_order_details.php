<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('ebook_order_details', function (Blueprint $table) {
            $table->id(); 
            $table->foreignId('ebook_order_id')->constrained('ebook_orders')->onDelete('cascade');
            $table->foreignId('ebook_id')->constrained('product_ebooks')->onDelete('cascade');
            $table->decimal('price', 15, 2)->nullable(false);
            $table->integer('quantity')->nullable(false)->unsigned()->default(1);
            $table->decimal('total_price', 15, 2)->nullable(false);
            $table->timestamps();

            $table->index('ebook_order_id', 'idx_ebook_order_details_order_id');
            $table->index('ebook_id', 'idx_ebook_order_details_ebook_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('ebook_order_details');
    }
};