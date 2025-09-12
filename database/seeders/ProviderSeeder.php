<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProviderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('providers')->insert([
            [
                'provider_name' => 'Giao hàng nhanh',
                'provider_status' => 'active',
            ],
            [
                'provider_name' => 'Giao hàng tiết kiệm',
                'provider_status' => 'active',
            ],
            [
                'provider_name' => 'Viettel Post',
                'provider_status' => 'active',
            ],
            [
                'provider_name' => 'Ninja Van',
                'provider_status' => 'inactive',
            ],
            
        ]);
    }
}
