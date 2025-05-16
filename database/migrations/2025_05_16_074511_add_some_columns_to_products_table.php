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
        Schema::table('products', function (Blueprint $table) {
            $table->integer('product_released_year')->nullable();
            $table->double('height')->nullable();
            $table->double('width')->nullable();
            $table->double('weight')->nullable();
            $table->integer('pages')->nullable();
            $table->enum('book_cover', ['Bìa cứng', 'Bìa mềm'])->default('Bìa mềm');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            //
        });
    }
};
