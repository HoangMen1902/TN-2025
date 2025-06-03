<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
    
        Schema::create('ratings', function (Blueprint $table) {
            $table->id();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->unsignedBigInteger('order_detail_id');
            $table->unsignedBigInteger('user_id');
            $table->text('review')->nullable();
            $table->tinyInteger('rating')->unsigned();  
            $table->json('images')->nullable();  
            $table->boolean('is_anonymous')->default(false);  
            $table->timestamps();

            $table->foreign('order_detail_id')->references('id')->on('order_details')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ratings');
    }
};
