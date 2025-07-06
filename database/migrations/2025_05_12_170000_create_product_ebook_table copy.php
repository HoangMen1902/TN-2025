<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEbooksTable extends Migration
{
    public function up()
    {
        Schema::create('product_ebooks', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->foreignId('product_id')->nullable()->constrained('products')->nullOnDelete();
            $table->text('description')->nullable();
            $table->string('author')->nullable();
            $table->string('cover_image')->nullable();
            $table->decimal('price', 10, 2)->default(0);
            $table->string('file_path');
            $table->enum('product_ebook_status', ['active', 'inactive', 'draft'])->default('active');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('product_ebooks');
    }
}
