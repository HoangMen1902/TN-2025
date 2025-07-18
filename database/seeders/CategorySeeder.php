<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
   public function run(): void
{
    DB::statement('SET FOREIGN_KEY_CHECKS=0;');
    DB::table('categories')->truncate();
    DB::statement('SET FOREIGN_KEY_CHECKS=1;');

    $tieuThuyetId = DB::table('categories')->insertGetId([
        'name' => 'Tiểu thuyết',
        'category_status' => 'active',
        "slug" => 'tieu-thuyet',
        'parent_id' => null,
    ]);

    $truyenNganId = DB::table('categories')->insertGetId([
        'name' => 'Truyện ngắn',
        'category_status' => 'active',
        "slug" => 'truyen-ngan',
        'parent_id' => null,
    ]);

    $khoaHocId = DB::table('categories')->insertGetId([
        'name' => 'Khoa học',
        'category_status' => 'active',
        "slug" => 'khoa-hoc',
        'parent_id' => null,
    ]);

    DB::table('categories')->insert([
        ['name' => 'Cổ tích', 'category_status' => 'active', 'slug' => 'co-tich', 'parent_id' => $truyenNganId],
        ['name' => 'Cười', 'category_status' => 'active', 'slug' => 'cuoi', 'parent_id' => $truyenNganId],
        ['name' => 'Vũ trụ', 'category_status' => 'active', 'slug' => 'vu-tru', 'parent_id' => $khoaHocId],
        ['name' => 'Toán học', 'category_status' => 'active', 'slug' => 'toan-hoc', 'parent_id' => $khoaHocId],
    ]);
}

}
