<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEbookChaptersTable extends Migration
{
    public function up()
    {
        Schema::create('ebook_chapters', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ebook_id');
            $table->string('chapter_name');
            $table->string('file_path');
            $table->integer('start_page')->nullable(); 
            $table->integer('end_page')->nullable(); 
            $table->longText('content')->nullable();  
            $table->timestamps();

            $table->foreign('ebook_id')->references('id')->on('product_ebooks')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('ebook_chapters');
    }
}