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
            // Thêm cột issued_by để xác định cách phát hành voucher
            // Các giá trị có thể là: 'manual' (thủ công), 'membership' (thành viên), 'point' (đổi điểm
            $table->enum('issued_by', ['manual', 'membership', 'point'])->after('voucher_scope');
        });
    }

    public function down(): void
    {
        Schema::table('vouchers', function (Blueprint $table) {
            $table->dropColumn(['is_redeemable', 'required_points', 'issued_by']);
        });
    }
};
