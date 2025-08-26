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
         Schema::table('orders', function (Blueprint $table) {
            $table->string('bank_code', 20)
                ->nullable()
                ->after('reason')
                ->comment('Mã ngân hàng theo VietQR');

            $table->string('bank_account_number', 50)
                ->nullable()
                ->after('bank_code')
                ->comment('Số tài khoản ngân hàng của khách hàng');

            $table->string('bank_account_name', 100)
                ->nullable()
                ->after('bank_account_number')
                ->comment('Tên chủ tài khoản ngân hàng của khách hàng');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            //
        });
    }
};
