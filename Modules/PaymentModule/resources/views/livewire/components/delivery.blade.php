<section class="bg-white p-6 rounded-lg shadow-sm">
    <h2 class="text-xl font-semibold mb-4">Phương thức vận chuyển</h2>
    <div class="space-y-4">
        <label class="flex items-center p-4 border border-neutral-300 rounded-lg cursor-pointer hover:border-black">
            <input type="radio" name="shipment_unit" value="Giao Hàng Nhanh" class="form-radio text-black" checked>
            <div class="ml-4">
                <div class="font-semibold text-sm">Giao tận nơi - Đơn vị Giao Hàng Nhanh</div>
                <div class="text-sm text-gray-600" wire:loading>Đang lấy phí vận chuyển..</div>
                <div class="text-sm text-gray-600" wire:loading.remove>
                    {{ 
                        $ghnFee
    ? 'Phí vận chuyển: '. number_format($ghnFee, 0, '', '.') . 'đ'
    : 'Chọn địa chỉ để xem phí ship' 
                    }}
                    • Nhận hàng từ {{ $ghnFrom ?? 3 }} - {{ $ghnTime ?? 5 }}
                    @if(!isset($ghnFrom) || !isset($ghnTime))
                        Ngày
                    @endif
                </div>
            </div>
        </label>
        <label class="flex items-center p-4 border border-neutral-300 rounded-lg cursor-pointer hover:border-black">
            <input type="radio" name="shipment_unit" value="Giao Hàng Tiết Kiệm" class="form-radio text-black">
            <div class="ml-4">
                <div class="font-semibold text-sm">Giao tận nơi - Đơn vị Giao Hàng Tiết Kiệm</div>
                <div class="text-sm text-gray-600">30.000đ • Nhận hàng từ 3 - 5 ngày</div>
            </div>
        </label>
        <label class="flex items-center p-4 border border-neutral-300 rounded-lg cursor-pointer hover:border-black">
            <input type="radio" name="shipment_unit" value="NinjaVan" class="form-radio text-black">
            <div class="ml-4">
                <div class="font-semibold text-sm">Giao tận nơi - Đơn vị ViettelPost</div>
                <div class="text-sm text-gray-600" wire:loading>Đang lấy phí vận chuyển..</div>
                <div class="text-sm text-gray-600" wire:loading.remove>
                    {{ 
                        $viettelFee
    ? 'Phí vận chuyển: '. number_format($viettelFee, 0, '', '.') . 'đ'
    : 'Chọn địa chỉ để xem phí ship' 
                    }}
                    • Nhận hàng trong vòng 48 giờ
                </div>
            </div>
        </label>
    </div>
</section>