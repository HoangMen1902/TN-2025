<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('user_ebook_chapter_statuses', function (Blueprint $table) {
            $table->id();
        
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('ebook_id')->constrained('product_ebooks')->onDelete('cascade');
            $table->foreignId('chapter_id')->constrained('ebook_chapters')->onDelete('cascade');
        
            $table->boolean('is_read')->default(false);
            $table->boolean('is_favorite')->default(false);
            $table->timestamps();
            $table->unsignedInteger('reading_position')->nullable();

            $table->unique(['user_id', 'chapter_id']); 
        });
    }

    public function down(): void
    {
        Schema::table('ebook_payment_details', function (Blueprint $table) {
            $table->dropColumn('is_paid');
        });
    }
};
