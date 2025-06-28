<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEbooksTable extends Migration
{
    public function up()
    {
        Schema::create('ebook_read_progress', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('ebook_id')->constrained('ebooks')->cascadeOnDelete();
            $table->integer('last_page')->default(1);
            $table->timestamps();
        });
        
    }

    public function down()
    {
        Schema::dropIfExists('ebooks');
    }
}
