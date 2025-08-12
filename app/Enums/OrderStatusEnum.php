<?php

declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class OrderStatusEnum extends Enum
{
    const DangXuLy       = 'Đang xử lý';
    const ChoThanhToan   = 'Chờ thanh toán';
    const DaThanhToan    = 'Đã thanh toán';
    const ChoDuyet       = 'Chờ duyệt';
    const VanChuyen      = 'Vận chuyển';
    const GiaoThatBai = 'Giao hàng thất bại';
    const ChoHoanTien    = 'Chờ hoàn tiền';
    const DaHoanTien     = 'Đã hoàn tiền';
    const DaGiao         = 'Đã giao';
    const DaHuy          = 'Đã hủy';
}
