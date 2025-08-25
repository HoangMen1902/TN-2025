<?php

declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class ShippingStatus extends Enum
{
    const CHUA_DANG_DON = 'chua_dang_don';
    const DA_TAO_DON = 'da_tao_don';
    const DANG_LAY_HANG = 'dang_lay_hang';
    const DA_LAY_HANG = 'da_lay_hang';
    const DANG_VAN_CHUYEN = 'dang_van_chuyen';
    const DA_GIAO_THANH_CONG = 'da_giao_thanh_cong';
    const GIAO_HANG_THAT_BAI = 'giao_hang_that_bai';
    const CHO_TRA_LAI = 'cho_tra_lai';
    const DA_TRA_LAI = 'da_tra_lai';
    const CO_SU_CO = 'co_su_co';
    const DA_HUY = 'da_huy';
    const CHO_DUYET_HOAN = 'cho_duyet_hoan';
    const DUYET_HOAN = 'duyet_hoan';
    const PHAT_THANH_CONG_TIEU_HUY = 'phat_thanh_cong_tieu_huy';


    public static function getLabel($value) {
        return match($value) {
            self::CHUA_DANG_DON => 'Chưa đăng đơn',
            self::DA_TAO_DON => 'Đã tạo đơn',
            self::DANG_LAY_HANG => 'Đang lấy hàng',
            self::DA_LAY_HANG => 'Đã lấy hàng',
            self::DANG_VAN_CHUYEN => 'Đang vận chuyển',
            self::DA_GIAO_THANH_CONG => 'Đã giao thành công',
            self::GIAO_HANG_THAT_BAI => 'Giao hàng thất bại',
            self::CHO_TRA_LAI => 'Chờ trả lại',
            self::DA_TRA_LAI => 'Đã trả lại',
            self::CO_SU_CO => 'Có sự cố',
            self::DA_HUY => 'Đã hủy',
            self::CHO_DUYET_HOAN => 'Chờ duyệt hoàn',
            self::DUYET_HOAN => 'Đã duyệt hoàn',
            self::PHAT_THANH_CONG_TIEU_HUY => 'Phát thành công - Tiêu hủy',
            default => self::getKey($value),
        };
    }
}
