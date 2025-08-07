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
        Schema::create('ebook_audio_jobs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ebook_id')->constrained('product_ebooks')->onDelete('cascade');
            $table->json('chapter_ids');
            $table->string('voice')->nullable();
            $table->float('speed')->default(1.0);
            $table->enum('status', ['pending', 'processing', 'completed', 'failed'])->default('pending');
            $table->text('output_path')->nullable();
            $table->boolean('is_locked_voice')->default(false);
            $table->timestamps();
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ebook_audio_jobs');
    }
};
