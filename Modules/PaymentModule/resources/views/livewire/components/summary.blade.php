<div class="lg:col-span-1">
    <div class="bg-white p-6 rounded-lg shadow-sm sticky top-4">
        <h2 class="text-xl font-semibold mb-4">Tóm tắt đơn hàng</h2>

        <!-- Product -->
        @php
            $totalPrice = 0;
        @endphp

        @foreach ($carts as $cart)
            @php
                $type = $cart->item_type;
                $price = 0;
                if ($type === 'combo') {
                    $price = $cart->combo->sale_price;
                } elseif ($type === 'sku') {
                    $unit_price = $cart->sku->sale_price ?? $cart->sku->price;

                    if (isset($flashsale_products[$cart->sku_id])) {
                        $discount_type = $flashsale_products[$cart->sku_id]['discount_type'];
                        $discount_amount = $flashsale_products[$cart->sku_id]['discount_amount'];

                        if ($discount_type === 'percent') {
                            $unit_price -= ($unit_price * $discount_amount / 100);
                        } elseif ($discount_type === 'specific') {
                            $unit_price -= $discount_amount;
                        }

                        if ($unit_price < 0) {
                            $unit_price = 0;
                        }
                    }

                    // $price += $cart->quantity * $unit_price;
                    $price = $unit_price;
                }
                $totalPrice += $cart->quantity * $price;
                $image = $cart->sku->images[0] ?? $cart->combo->images[0];
            @endphp

            <div class="flex items-start space-x-4 mb-4 pb-2 border-b border-b-neutral-300">
                <img src="{{ asset('storage/' . $image) }}" alt="Product Image" class="w-20 h-20 rounded-lg">
                <div class="flex-1">
                    <h3 class="font-medium line-clamp-2">
                        {{ $cart->sku->product->name ?? $cart->combo->combo_name }}
                    </h3>
                    @if ($cart->sku)
                        <p class="text-sm text-gray-600">
                            {{ $cart->sku->options->first()?->name ?? 'Không có tùy chọn' }}:
                            {{ $cart->sku->optionValues->first()?->value_name ?? 'Không có giá trị' }}
                        </p>
                    @else
                        <p class="text-sm text-gray-600">Loại: Combo</p>
                    @endif
                    <p class="text-sm text-gray-600">Số lượng: {{ $cart->quantity }}</p>
                    <p class="font-medium text-sm text-red-600 mt-1">Tổng:
                        {{ number_format($cart->quantity * $price) }} VNĐ
                    </p>
                </div>
            </div>

            <input type="hidden" name="cart_id[]" value="{{ $cart->id }}">
        @endforeach


        <!-- Promo Code -->
        <!-- Promo Code -->
        <div class="mb-4 pb-4 border-b border-b-neutral-300">
            <div class="flex space-x-2">
                <input type="text" wire:model.defer="voucherCode" name="voucher_code"
                    class="flex-1 border border-gray-300 rounded-lg px-3 py-2" placeholder="Nhập mã giảm giá">
                <button type="button" wire:click="applyVoucher"
                    class="px-2 py-2 bg-blue-500 text-white rounded-lg transition hover:bg-blue-700">
                    Áp dụng
                </button>
            </div>

            @if ($voucherMessage)
                <p class="text-sm mt-2 {{ $voucherDiscount > 0 ? 'text-green-600' : 'text-red-600' }}">
                    {{ $voucherMessage }}
                </p>
            @endif
            <button type="button" onclick="toggleVoucherModal()" class="text-sm text-blue-600 no-underline mt-2">Xem mã
                giảm giá khả dụng</button>

            <!-- Modal -->
            <!-- Nền modal -->
            <div id="voucherModal"
                class="fixed inset-0 z-50 hidden backdrop-blur-sm bg-black/30 flex items-center justify-center px-4">
                <!-- Hộp modal -->
                <div
                    class="bg-white w-full max-w-md p-6 rounded-2xl shadow-2xl relative border border-gray-200 transition-transform scale-100">

                    <!-- Nút đóng -->
                    <button type="button" onclick="toggleVoucherModal()"
                        class="absolute top-3 right-3 text-gray-400 hover:text-gray-700 text-2xl leading-none focus:outline-none">&times;</button>

                    <!-- Tiêu đề -->
                    <h3 class="text-xl font-bold mb-5 text-center text-gray-800">🎁 Mã giảm giá khả dụng</h3>

                    @if ($availableVouchers->isEmpty())
                        <p class="text-sm text-gray-600 text-center">Hiện chưa có mã giảm giá nào khả dụng.</p>
                    @else
                        <ul class="space-y-3 max-h-60 overflow-y-auto custom-scrollbar pr-1">
                            @foreach ($availableVouchers as $voucher)
                                @php
                                    $voucher_scope = $voucher->voucher_scope;
                                    $voucher_type = '';
                                    $voucher_max = $voucher->max_discount_amount ?? 0;
                                    if($voucher_scope === 'shipping') {
                                        $voucher_type = 'Giảm phí vận chuyển';
                                    } elseif($voucher_scope === 'global') {
                                        $voucher_type = "Giảm phí đơn hàng";
                                    }
                                @endphp
                                    <li class="border border-gray-200 p-4 rounded-xl hover:bg-blue-50 cursor-pointer transition"
                                    onclick="selectVoucher('{{ $voucher->voucher_code }}')">
                                    <div class="flex justify-between items-center">
                                        <p class="font-semibold text-blue-600 text-base">{{ $voucher->voucher_code }}</p>
                                        <span class="bg-blue-100 text-blue-700 text-xs font-medium px-2 py-1 rounded">
                                            @if ($voucher->voucher_type === 'percent')
                                                {{ $voucher->reduced_amount }}%
                                            @else
                                                {{ number_format($voucher->reduced_amount) }}₫
                                            @endif
                                        </span>
                                    </div>
                                    
                                    <p class="text-sm text-gray-600 mt-1">
                                        Loại Voucher: {{ $voucher_type }}
                                    </p>
                                    <p class="text-sm text-gray-600 mt-1">
                                        Áp dụng cho đơn từ {{ number_format($voucher->requirement_price) }}₫
                                    </p>
                                                                        <p class="text-sm text-gray-600 mt-1">
                                        Giảm tối đa: {{ number_format($voucher_max) }}₫
                                    </p>
                                    <p class="text-xs text-gray-400 mt-1">Hạn: {{ $voucher->expired_at->format('d/m/Y') }}</p>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>

        </div>




        <div class="space-y-2 mb-4 pb-4 border-b border-b-neutral-300">
            <div class="flex justify-between">
                <span class="text-gray-600">Tổng</span>
                <span>{{ number_format($originalPrice) }} VNĐ</span>
            </div>
            @if ($voucherDiscount > 0)
                <div class="flex justify-between">
                    <span class="text-gray-600">Giảm giá</span>
                    <span class="text-red-600">-{{ number_format($voucherDiscount) }} VNĐ</span>
                </div>
            @endif
            <div class="flex justify-between">
                <span class="text-gray-600">Phí ship</span>
                <span class="text-green-600">{{ number_format($shipping_fee) }} VNĐ</span>
            </div>
        </div>

        <div class="flex justify-between items-center mb-6">
            <span class="text-lg font-semibold">Tổng</span>
            <span class="text-lg font-semibold">{{ number_format($finalPrice) }} VNĐ</span>
        </div>

        @php
            $hasSelectedAddress = session()->has('selected_address_id');
        @endphp

        <button type="button" wire:loading wire:loading.attr="disabled"
            class="w-full bg-primary text-white py-4 rounded-full hover:bg-gray-800 flex items-center justify-center transition duration-300">
            <span>Đang xác thực thông tin..</span>
            <i class="fas fa-lock ml-2"></i>
        </button>


        <button wire:loading.remove type="{{$submitable ? 'submit' : 'button'}}"
            class="w-full {{$submitable ? 'bg-primary' : 'bg-blue-400'}} text-white py-4 rounded-full {{$submitable ? 'hover:bg-gray-800 transition duration-300' : ''}} flex items-center justify-center ">
            <span>Đặt hàng</span>
            <i class="fas fa-lock ml-2"></i>
        </button>








        <div class="mt-4 text-sm text-gray-600 text-center">
            <p>Thanh toán đồng nghĩa với việc bạn chấp nhận</p>
            <p><a href="#" class="underline">Điều khoản sử dụng</a> của chúng tôi</p>
        </div>
    </div>
</div>
<script>
    function toggleVoucherModal() {
        const modal = document.getElementById('voucherModal');
        modal.classList.toggle('hidden');
    }

    function selectVoucher(voucherCode) {
        const input = document.querySelector('input[wire\\:model\\.defer="voucherCode"]');
        input.value = voucherCode;
        input.dispatchEvent(new Event('input', { bubbles: true })); // để Livewire bắt được

        toggleVoucherModal(); // đóng modal
    }
</script>