<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('ebook_payment_details', function (Blueprint $table) {
            $table->id(); 
            $table->foreignId('ebook_order_id')->constrained('ebook_orders')->onDelete('cascade');
            $table->string('payment_method', 255)->nullable(false);
            $table->string('payment_id', 255)->nullable();
            $table->string('tracking_id', 36)->unique()->nullable(false);
            $table->timestamps();

            $table->index('order_id', 'idx_ebook_payment_details_order_id');
            $table->index('tracking_id', 'idx_ebook_payment_details_tracking_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('ebook_payment_details');
    }
};