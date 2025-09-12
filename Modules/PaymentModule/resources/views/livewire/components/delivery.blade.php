<section class="bg-white p-6 rounded-lg shadow-sm">
    <h2 class="text-xl font-semibold mb-4">Phương thức vận chuyển</h2>
    <div class="space-y-4">
        <label
            class="{{in_array('Giao hàng nhanh', $active_unit) ? '' : 'hidden'}}  flex items-center p-4 border border-neutral-300 rounded-lg cursor-pointer hover:border-black">
            <input type="radio" name="shipment_unit" value="Giao Hàng Nhanh" wire:click="$refresh"
                wire:model="selected_unit" class="form-radio text-black" checked>
            <div class="ml-4">
                <div class="font-semibold text-sm">Giao tận nơi - Đơn vị Giao Hàng Nhanh</div>
                <div class="text-sm text-gray-600" wire:loading>Đang lấy phí vận chuyển..</div>
                <div class="text-sm text-gray-600" wire:loading.remove>
                    {{ 
                        $ghnFee
    ? 'Phí vận chuyển: ' . number_format($ghnFee, 0, '', '.') . 'đ'
    : 'Chọn địa chỉ để xem phí ship' 
                    }}
                    • Nhận hàng từ {{ $ghnFrom ?? 3 }} - {{ $ghnTime ?? 5 }}
                    @if(!isset($ghnFrom) || !isset($ghnTime))
                        Ngày
                    @endif
                </div>
            </div>
        </label>
        <label
            class="{{in_array('Giao hàng tiết kiệm', $active_unit) ? '' : 'hidden'}}  flex items-center p-4 border border-neutral-300 rounded-lg cursor-pointer hover:border-black">
            <input type="radio" name="shipment_unit" value="Giao hàng tiết kiệm" wire:click="$refresh"
                wire:model="selected_unit" class="form-radio text-black" checked>
            <div class="ml-4">
                <div class="font-semibold text-sm">Giao tận nơi - Đơn vị Giao hàng tiết kiệm</div>
                <div class="text-sm text-gray-600" wire:loading>Đang lấy phí vận chuyển..</div>
                <div class="text-sm text-gray-600" wire:loading.remove>
                    {{ 
                        $ghnFee
    ? 'Phí vận chuyển: ' . number_format($ghnFee, 0, '', '.') . 'đ'
    : 'Chọn địa chỉ để xem phí ship' 
                    }}
                    • Nhận hàng từ {{ $ghnFrom ?? 3 }} - {{ $ghnTime ?? 5 }}
                    @if(!isset($ghnFrom) || !isset($ghnTime))
                        Ngày
                    @endif
                </div>
            </div>
        </label>
        <label
            class="{{in_array('Ninja Van', $active_unit) ? '' : 'hidden'}}  flex items-center p-4 border border-neutral-300 rounded-lg cursor-pointer hover:border-black">
            <input type="radio" name="shipment_unit" value="Ninja Van" wire:click="$refresh" wire:model="selected_unit"
                class="form-radio text-black" checked>
            <div class="ml-4">
                <div class="font-semibold text-sm">Giao tận nơi - Đơn vị Ninja Van</div>
                <div class="text-sm text-gray-600" wire:loading>Đang lấy phí vận chuyển..</div>
                <div class="text-sm text-gray-600" wire:loading.remove>
                    {{
                    $ghnFee
                    ? 'Phí vận chuyển: ' . number_format($ghnFee, 0, '', '.') . 'đ'
                    : 'Chọn địa chỉ để xem phí ship'
                    }}
                    • Nhận hàng từ {{ $ghnFrom ?? 3 }} - {{ $ghnTime ?? 5 }}
                    @if(!isset($ghnFrom) || !isset($ghnTime))
                    Ngày
                    @endif
                </div>
            </div>
        </label>
        <label
        class="{{in_array('Viettel Post', $active_unit) ? '' : 'hidden'}} flex items-center p-4 border border-neutral-300 rounded-lg cursor-pointer hover:border-black">
        <input type="radio" wire:click="$refresh" wire:model="selected_unit" name="shipment_unit"
        value="Viettel Post" class="form-radio text-black ">
        <div class="ml-4">
            <div class="font-semibold text-sm">Giao tận nơi - Đơn vị ViettelPost</div>
            <div class="text-sm text-gray-600" wire:loading>Đang lấy phí vận chuyển..</div>
                <div class="text-sm text-gray-600" wire:loading.remove>
                    {{ 
                        $viettelFee
                        ? 'Phí vận chuyển: ' . number_format($viettelFee, 0, '', '.') . 'đ'
    : 'Chọn địa chỉ để xem phí ship' 
                    }}
                    • Nhận hàng trong vòng 48 giờ
                </div>
            </div>
        </label>
        <label
            class="flex items-center p-4 border border-neutral-300 rounded-lg {{empty($active_unit) || !isset($active_unit) ? '' : 'hidden'}}">
            <div class="ml-4">
                <div class="font-semibold text-sm text-neutral-400">Không có đơn vị vận chuyển nào hoạt động</div>
                <div class="text-sm  text-neutral-400">Các đơn vị vận chuyển hiện thời đang không hoạt động</div>
            </div>
        </label>
    </div>
</section>