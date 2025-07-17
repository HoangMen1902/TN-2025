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
        Schema::table('provider_districts', function (Blueprint $table) {
            $table->string('provider_province_code')->nullable();
            $table->foreign('provider_province_code')->references('provider_province_code')->on('provider_provinces')->onDelete('cascade');
            $table->index('provider_district_code');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('provider_districts', function (Blueprint $table) {
            //
        });
    }
};
