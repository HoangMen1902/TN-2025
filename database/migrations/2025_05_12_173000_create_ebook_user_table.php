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
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('ebook_id');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('ebook_id')->references('id')->on('ebooks')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('ebook_user');
    }
}
