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
        Schema::table('provider_wards', function (Blueprint $table) {
            $table->string('provider_district_code')->nullable();
            $table->foreign('provider_district_code')->references('provider_district_code')->on('provider_districts')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('provider_wards', function (Blueprint $table) {
            //
        });
    }
};
