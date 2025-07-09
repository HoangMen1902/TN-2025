<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PaymentMethodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('payment_methods')->insert([

             [
                'method_name' => 'payos',
                'method_status' => 'active'
            ],
            [
                'method_name' => 'International',
                'method_status' => 'active'
            ],
            [
                'method_name' => 'COD',
                'method_status' => 'active'
            ],
        ]);
    }
}
