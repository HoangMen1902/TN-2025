<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('ebook_tag', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ebook_id');
            $table->unsignedBigInteger('tag_id');
            $table->timestamps();

            $table->foreign('ebook_id')->references('id')->on('product_ebooks')->onDelete('cascade');
            $table->foreign('tag_id')->references('id')->on('related_tags')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('ebook_tag');
    }
};