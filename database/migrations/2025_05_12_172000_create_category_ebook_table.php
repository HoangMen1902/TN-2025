<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEbooksTable extends Migration
{
    public function up()
    {
        Schema::create('category_ebook', function (Blueprint $table) {
            $table->foreignId('ebook_id')->constrained('ebooks')->cascadeOnDelete();
            $table->foreignId('category_id')->constrained('categories')->cascadeOnDelete();
            $table->primary(['ebook_id', 'category_id']);
        });
        
        
    }

    public function down()
    {
        Schema::dropIfExists('ebooks');
    }
}
