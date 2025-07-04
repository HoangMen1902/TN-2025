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
    Schema::create('won_prizes', function (Blueprint $table) {
        $table->id();
        $table->foreignId('prize_id')->constrained('prizes')->onDelete('cascade');
        $table->unsignedBigInteger('user_id')->nullable(); // nếu có đăng nhập
        $table->string('username')->nullable(); // nếu không có user
        $table->timestamp('won_at')->useCurrent();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('won_prizes');
    }
};
