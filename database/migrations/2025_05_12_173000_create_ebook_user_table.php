<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEbooksTable extends Migration
{
    public function up()
    {
        Schema::create('ebook_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('ebook_id')->constrained('ebooks')->cascadeOnDelete();
            $table->timestamps();
        });
        
        
        
    }

    public function down()
    {
        Schema::dropIfExists('ebooks');
    }
}
