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
        Schema::create('vouchers', function (Blueprint $table) {
            $table->id();
            $table->string("voucher_name");
            $table->double("requirement_price");
            $table->double("reduced_amount");
            $table->enum("voucher_type", ["percent", "amount"]);
            $table->dateTime("expired_at");
            $table->enum("voucher_status", ["active", "inactive"]);
            $table->timestamps();
            $table->softDeletes(); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vouchers');
    }
};
