<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('vouchers', function (Blueprint $table) {
            $table->string('voucher_code')->unique()->after('id');
            $table->integer('quantity')->nullable()->after('voucher_type');
            $table->integer('usage_per_user')->nullable()->after('quantity');
            $table->enum('voucher_scope', ['global', 'shipping', 'category'])->default('global')->after('usage_per_user');
            $table->decimal('max_discount_amount', 10, 2)->nullable()->after('reduced_amount');
            $table->dateTime('start_at')->nullable()->after('voucher_scope');
        });
    }

    public function down(): void
    {
        Schema::table('vouchers', function (Blueprint $table) {
            $table->dropColumn([
                'voucher_code',
                'quantity',
                'usage_per_user',
                'voucher_scope',
                'max_discount_amount',
                'start_at'
            ]);
        });
    }
};
