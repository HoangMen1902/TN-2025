<?php


namespace App\Models;

use App\Enums\ViettelPostStatusEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'orders';

    protected $fillable = [
        'orders_status',
        'user_id',
        'address',
        'is_approved',
        'phone',
        'reason',
        'contact_email',
        'customer_name',
        'total_price',
        'shipment_price',
        'shipping_order_code',
        'shipping_status',
        'shipping_info',
        'province_id',
        'district_id',
        'ward_id',
        'amount_decrease',
        'is_paid',
        'reduced_amount',
        'voucher_id',
        'order_code',
    ];

    protected $casts = [
        'is_approved' => 'boolean',
        'shipping_info' => 'array'
    ];

    // Constants cho shipping status
    const SHIPPING_STATUS_CHUA_DANG_DON = 'chua_dang_don';
    const SHIPPING_STATUS_DA_TAO_DON = 'da_tao_don';
    const SHIPPING_STATUS_DANG_LAY_HANG = 'dang_lay_hang';
    const SHIPPING_STATUS_DA_LAY_HANG = 'da_lay_hang';
    const SHIPPING_STATUS_DANG_VAN_CHUYEN = 'dang_van_chuyen';
    const SHIPPING_STATUS_DA_GIAO_THANH_CONG = 'da_giao_thanh_cong';
    const SHIPPING_STATUS_GIAO_HANG_THAT_BAI = 'giao_hang_that_bai';
    const SHIPPING_STATUS_CHO_TRA_LAI = 'cho_tra_lai';
    const SHIPPING_STATUS_DA_TRA_LAI = 'da_tra_lai';
    const SHIPPING_STATUS_CO_SU_CO = 'co_su_co';
    const SHIPPING_STATUS_DA_HUY = 'da_huy';

    // Helper method để lấy tất cả shipping status
    public static function getShippingStatuses()
    {
        return [
            self::SHIPPING_STATUS_CHUA_DANG_DON => 'Chưa đăng đơn',
            self::SHIPPING_STATUS_DA_TAO_DON => 'Đã tạo đơn',
            self::SHIPPING_STATUS_DANG_LAY_HANG => 'Đang lấy hàng',
            self::SHIPPING_STATUS_DA_LAY_HANG => 'Đã lấy hàng',
            self::SHIPPING_STATUS_DANG_VAN_CHUYEN => 'Đang vận chuyển',
            self::SHIPPING_STATUS_DA_GIAO_THANH_CONG => 'Đã giao thành công',
            self::SHIPPING_STATUS_GIAO_HANG_THAT_BAI => 'Giao hàng thất bại',
            self::SHIPPING_STATUS_CHO_TRA_LAI => 'Chờ trả lại',
            self::SHIPPING_STATUS_DA_TRA_LAI => 'Đã trả lại',
            self::SHIPPING_STATUS_CO_SU_CO => 'Có sự cố',
            self::SHIPPING_STATUS_DA_HUY => 'Đã hủy'
        ];
    }

    public static function mapViettelPostStatusToOrderStatus(int $vtpStatusCode): ?string
    {
        $groupName = ViettelPostStatusEnum::getGroupName($vtpStatusCode);

        $map = [
            'CHUA_DUYET'               => self::SHIPPING_STATUS_CHUA_DANG_DON,
            'DA_DUYET'                  => self::SHIPPING_STATUS_DA_TAO_DON,
            'DA_GUI_CUA_HANG_TIEN_LOI'  => self::SHIPPING_STATUS_DANG_VAN_CHUYEN,
            'DA_HUY'                    => self::SHIPPING_STATUS_DA_HUY,
            'DA_LAY_HANG'               => self::SHIPPING_STATUS_DA_LAY_HANG,
            'DANG_VAN_CHUYEN'           => self::SHIPPING_STATUS_DANG_VAN_CHUYEN,
            'DANG_GIAO_HANG'            => self::SHIPPING_STATUS_DANG_VAN_CHUYEN,
            'GIAO_HANG_THAT_BAI'        => self::SHIPPING_STATUS_GIAO_HANG_THAT_BAI,
            'DUYET_HOAN'                => self::SHIPPING_STATUS_CHO_TRA_LAI,
            'PHAT_THANH_CONG_TIEU_HUY'  => self::SHIPPING_STATUS_DA_TRA_LAI,
            'HOAN_THANH_CONG'           => self::SHIPPING_STATUS_DA_TRA_LAI,
            'CHO_DUYET_HOAN'            => self::SHIPPING_STATUS_CHO_TRA_LAI,
            'GIAO_HANG_THANH_CONG'      => self::SHIPPING_STATUS_DA_GIAO_THANH_CONG,
        ];

        return $groupName && isset($map[$groupName]) ? $map[$groupName] : null;
    }


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class);
    }

    public function getCalculatedTotalPriceAttribute()
    {
        return $this->orderDetails->sum(function ($item) {
            return $item->price * $item->quantity;
        });
    }


    public function webhook()
    {
        return $this->hasMany(WebhookShippingBills::class);
    }

    public function paymentDetail()
    {
        return $this->hasOne(\App\Models\PaymentDetail::class, 'order_id');
    }
}
