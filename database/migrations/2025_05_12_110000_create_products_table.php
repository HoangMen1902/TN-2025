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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255);
            $table->text('description')->nullable();
            $table->integer('product_released_year');
            $table->string('author');
            $table->string('slug');
            $table->string('book_cover');
            $table->integer('order_by')->default(1);
            $table->integer('pages');
            $table->double('weight');
            $table->double('height');
            $table->double('width');
            $table->text('short_description')->nullable();
            $table->enum('product_status', ['active', 'inactive', 'draft'])->default('active');
            $table->text('thumbnail')->nullable();
            $table->foreignId('publisher_id')->constrained()->onDelete('cascade');
            $table->dateTime('published_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
