<?php
// filepath: d:\Workspace\duantotnghiepSource\TN-2025\database\migrations\2025_07_09_101648_add_shipping_columns_to_orders_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('shipping_order_code')->nullable();
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
            ])->default('chua_dang_don');
            $table->json('shipping_info')->nullable();
        });
    }

    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['shipping_order_code', 'shipping_status', 'shipping_info']);
        });
    }
};