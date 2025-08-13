<?php

declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class ViettelPostStatusEnum extends Enum
{
    const NEW_ORDER = -100;
    const SENT_AT_POST_OFFICE = -108;
    const SENT_AT_COLLECTION_POINT = -109;
    const HANDOVER_TO_POST_OFFICE = -110;

    const RECEIVED_FROM_PARTNER = 100;
    const CANCELLATION_REQUEST_BY_VTP = 101;
    const PENDING_PROCESSING = 102;
    const ASSIGNED_TO_POST_OFFICE = 103;
    const ASSIGNED_TO_COURIER_PICKUP = 104;
    const COURIER_RECEIVED = 105;
    const PARTNER_REQUEST_RETURN = 106;
    const PARTNER_REQUEST_CANCEL_API = 107;

    const RECEIVED_FROM_COURIER_ORIGIN = 200;
    const CANCEL_WAYBILL = 201;
    const EDIT_WAYBILL = 202;

    const CLOSE_DELIVERY_FILE = 300;
    const CLOSE_BAG = 301;
    const CLOSE_MAIL_TRIP = 302;
    const CLOSE_TRUCK_ROUTE = 303;

    const RECEIVE_BILL_OF_LADING = 400;
    const RECEIVE_BAG = 401;
    const RECEIVE_MAIL_TRIP = 402;
    const RECEIVE_TRUCK = 403;

    const DELIVERY_ATTEMPT = 500;
    const DELIVERY_SUCCESS = 501;
    const RETURN_TO_ORIGIN = 502;
    const CANCEL_BY_CUSTOMER = 503;
    const RETURN_TO_SENDER_SUCCESS = 504;
    const RETURN_NOTIFICATION_TO_ORIGIN = 505;
    const CUSTOMER_ABSENT = 506;
    const CUSTOMER_PICKUP_AT_POST_OFFICE = 507;
    const REDELIVERY = 508;
    const FORWARD_TO_OTHER_POST_OFFICE = 509;
    const CANCEL_DELIVERY_ASSIGNMENT = 510;
    const RETURN_APPROVED_BY_POST_OFFICE = 515;
    const DELIVERY_EXTENSION_REQUEST = 550;

    public const GROUPS = [
        'CHUA_DUYET' => [-100],
        'DA_DUYET' => [100, 102, 103, 104, -108],
        'DA_GUI_CUA_HANG_TIEN_LOI' => [-109, -110],
        'DA_HUY' => [107, 201],
        'DA_LAY_HANG' => [105],
        'DANG_VAN_CHUYEN' => [200, 202, 300, 320, 400],
        'DANG_GIAO_HANG' => [500, 506, 570, 508, 509, 550],
        'GIAO_HANG_THAT_BAI' => [507],
        'DUYET_HOAN' => [505, 502, 515],
        'PHAT_THANH_CONG_TIEU_HUY' => [503],
        'HOAN_THANH_CONG' => [504],
        'CHO_DUYET_HOAN' => [505],
        'GIAO_HANG_THANH_CONG' => [501],
    ];
    public static function getGroupName(int $statusCode): ?string
    {
        foreach (self::GROUPS as $groupName => $codes) {
            if (in_array($statusCode, $codes, true)) {
                return $groupName;
            }
        }
        return null;
    }

    public static function getDescription($value): string
    {

        return match ($value) {
            self::NEW_ORDER => 'Đơn hàng mới tạo, chưa duyệt',
            self::SENT_AT_POST_OFFICE => 'Đơn hàng gửi tại bưu cục',
            self::SENT_AT_COLLECTION_POINT => 'Đơn hàng đã gửi tại điểm thu gom',
            self::HANDOVER_TO_POST_OFFICE => 'Đơn hàng đang bàn giao qua bưu cục',

            self::RECEIVED_FROM_PARTNER => 'Tiếp nhận đơn hàng từ đối tác',
            self::CANCELLATION_REQUEST_BY_VTP => 'ViettelPost yêu cầu hủy đơn hàng',
            self::PENDING_PROCESSING => 'Đơn hàng chờ xử lý',
            self::ASSIGNED_TO_POST_OFFICE => 'Giao cho bưu cục xử lý đơn hàng',
            self::ASSIGNED_TO_COURIER_PICKUP => 'Giao cho bưu tá đi nhận',
            self::COURIER_RECEIVED => 'Bưu tá đã nhận hàng',
            self::PARTNER_REQUEST_RETURN => 'Đối tác yêu cầu lấy lại hàng',
            self::PARTNER_REQUEST_CANCEL_API => 'Đối tác yêu cầu hủy qua API',

            self::RECEIVED_FROM_COURIER_ORIGIN => 'Nhận từ bưu tá - Bưu cục gốc',
            self::CANCEL_WAYBILL => 'Hủy nhập phiếu gửi',
            self::EDIT_WAYBILL => 'Sửa phiếu gửi',

            self::CLOSE_DELIVERY_FILE => 'Đóng file giao hàng',
            self::CLOSE_BAG => 'Đóng túi gói (vận chuyển đi từ)',
            self::CLOSE_MAIL_TRIP => 'Đóng chuyến thư (vận chuyển đi từ)',
            self::CLOSE_TRUCK_ROUTE => 'Đóng tuyến xe (vận chuyển đi từ)',

            self::RECEIVE_BILL_OF_LADING => 'Nhận bảng kê đến',
            self::RECEIVE_BAG => 'Nhận túi gói',
            self::RECEIVE_MAIL_TRIP => 'Nhận chuyến thư',
            self::RECEIVE_TRUCK => 'Nhận chuyến xe',

            self::DELIVERY_ATTEMPT => 'Giao bưu tá đi phát',
            self::DELIVERY_SUCCESS => 'Thành công - Phát thành công',
            self::RETURN_TO_ORIGIN => 'Chuyển hoàn bưu cục gốc',
            self::CANCEL_BY_CUSTOMER => 'Hủy - Theo yêu cầu khách hàng',
            self::RETURN_TO_SENDER_SUCCESS => 'Thành công - Chuyển trả người gửi',
            self::RETURN_NOTIFICATION_TO_ORIGIN => 'Tồn - Thông báo chuyển hoàn bưu cục gốc',
            self::CUSTOMER_ABSENT => 'Tồn - Khách hàng nghỉ, không có nhà',
            self::CUSTOMER_PICKUP_AT_POST_OFFICE => 'Tồn - Khách hàng đến bưu cục nhận',
            self::REDELIVERY => 'Phát tiếp',
            self::FORWARD_TO_OTHER_POST_OFFICE => 'Chuyển tiếp bưu cục khác',
            self::CANCEL_DELIVERY_ASSIGNMENT => 'Hủy phân công phát',
            self::RETURN_APPROVED_BY_POST_OFFICE => 'Bưu cục phát duyệt hoàn',
            self::DELIVERY_EXTENSION_REQUEST => 'Đơn vị yêu cầu phát tiếp',

            default => (string) parent::getDescription($value),
        };
    }


        public static function getOrderStatusLabel(int $statusCode): ?string
    {
        $groupName = self::getGroupName($statusCode);

        $map = [
            'CHUA_DUYET'               => OrderStatusEnum::ChoDuyet,
            'DA_DUYET'                  => OrderStatusEnum::DangXuLy,
            'DA_GUI_CUA_HANG_TIEN_LOI'  => OrderStatusEnum::VanChuyen,
            'DA_HUY'                    => OrderStatusEnum::DaHuy,
            'DA_LAY_HANG'               => OrderStatusEnum::VanChuyen,
            'DANG_VAN_CHUYEN'           => OrderStatusEnum::VanChuyen,
            'DANG_GIAO_HANG'            => OrderStatusEnum::VanChuyen,
            'GIAO_HANG_THAT_BAI'        => OrderStatusEnum::GiaoThatBai,
            'DUYET_HOAN'                => OrderStatusEnum::ChoHoanTien,
            'PHAT_THANH_CONG_TIEU_HUY'  => OrderStatusEnum::DaHoanTien,
            'HOAN_THANH_CONG'           => OrderStatusEnum::DaHoanTien,
            'CHO_DUYET_HOAN'            => OrderStatusEnum::ChoHoanTien,
            'GIAO_HANG_THANH_CONG'      => OrderStatusEnum::DaGiao,
        ];

        return $groupName && isset($map[$groupName]) ? $map[$groupName] : null;
    }
}
