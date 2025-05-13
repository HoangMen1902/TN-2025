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
        Schema::create('product_previews', function (Blueprint $table) {
            $table->id();
            $table->text('file_path');
            $table->enum('format', ['image', 'video', 'pdf', 'other']);
            $table->string('file_name');
            $table->bigInteger('file_size')->unsigned();
            $table->boolean('is_active')->default(true);
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_previews');
    }
};
