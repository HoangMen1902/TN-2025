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
            $table->enum('shipping_status', [
                'chua_dang_don',       
                'da_tao_don',          
                'dang_lay_hang',       
                'da_lay_hang',       
                'dang_van_chuyen',     
                'da_giao_thanh_cong',  
                'giao_hang_that_bai',  
                'cho_tra_lai',    
                'da_tra_lai',       
                'co_su_co',        
                'da_huy',
                'cho_duyet_hoan',         
                'duyet_hoan',             
                'phat_thanh_cong_tieu_huy'
            ])->default('chua_dang_don')->change();
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->enum('shipping_status', [
                'chua_dang_don',       
                'da_tao_don',          
                'dang_lay_hang',       
                'da_lay_hang',       
                'dang_van_chuyen',     
                'da_giao_thanh_cong',  
                'giao_hang_that_bai',  
                'cho_tra_lai',    
                'da_tra_lai',       
                'co_su_co',        
                'da_huy' 
            ])->default('chua_dang_don')->change();
        });
    
    }
};
