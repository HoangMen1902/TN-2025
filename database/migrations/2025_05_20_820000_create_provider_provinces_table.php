<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('provider_provinces', function (Blueprint $table) {
            $table->id();
            $table->foreignId('provider_id')->constrained('providers')->onDelete('cascade');
            $table->foreignId('province_id')->nullable()->constrained('provinces')->onDelete('cascade');
            $table->string('provider_province_code', 255);
            $table->string('provider_province_name', 255);
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('provider_provinces');
    }
};
