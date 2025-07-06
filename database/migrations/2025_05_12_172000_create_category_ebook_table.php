<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCategoryEbookTable extends Migration
{
    public function up()
    {
        Schema::create('category_ebook', function (Blueprint $table) {
            $table->id(); // PK: ID
            $table->unsignedBigInteger('ebook_id');
            $table->unsignedBigInteger('category_id');
            $table->timestamps();

            $table->foreign('ebook_id')->references('id')->on('ebooks')->onDelete('cascade');
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('category_ebook');
    }
}