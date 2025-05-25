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
        Schema::create('flashsale_discounts', function(Blueprint $table) {
            $table->id();
            $table->enum('discount_type', ['percent', 'specific']);
            $table->double('discount_amount');
            $table->unsignedBigInteger('flashsale_id');
            $table->foreign('flashsale_id')->references('id')->on('flashsales')->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
public function down(): void
{
    Schema::dropIfExists('flashsale_discounts');
}
};
