<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('vouchers', function (Blueprint $table) {
            // Cho phép đổi bằng điểm
            $table->boolean('is_redeemable')->nullable()->after('voucher_scope');

            // Số điểm cần để đổi voucher
            $table->integer('required_points')->nullable()->after('is_redeemable');

        });
    }

    public function down(): void
    {
        Schema::table('vouchers', function (Blueprint $table) {
            $table->dropColumn(['is_redeemable', 'required_points']);
        });
    }
};

