<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class NotificationFactory extends Factory
{
    public function definition(): array
    {
        $categories = ['Đơn hàng', 'Tài khoản', 'Khuyến mãi', 'Ưu đãi độc quyền'];
        $category = $this->faker->randomElement($categories);

        $titleMap = [
            'Đơn hàng' => ['Đơn hàng #12345 đang được giao', 'Đơn hàng #12346 đã giao thành công'],
            'Tài khoản' => ['Cập nhật mật khẩu thành công', 'Thông báo đăng nhập bất thường'],
            'Khuyến mãi' => ['Nhận voucher 50k cho đơn từ 199k', 'Giảm giá 30% cho sản phẩm A'],
            'Ưu đãi độc quyền' => ['Ưu đãi VIP dành riêng cho bạn', 'Ưu đãi đặc biệt tháng này'],
        ];

        $title = $this->faker->randomElement($titleMap[$category]);

        $contentMap = [
            'Đơn hàng' => 'Thông tin về đơn hàng của bạn được cập nhật.',
            'Tài khoản' => 'Thông tin tài khoản của bạn đã thay đổi.',
            'Khuyến mãi' => 'Nhanh tay nhận ưu đãi mới nhất.',
            'Ưu đãi độc quyền' => 'Ưu đãi chỉ dành riêng cho khách hàng VIP.',
        ];

        return [
            'notification_type' => $category,
            'name' => $title,
            'content' => $contentMap[$category],
            'thumbnail' => '',
        ];
    }
}