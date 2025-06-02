<div class="lg:col-span-1">
    <div class="bg-white p-6 rounded-lg shadow-sm sticky top-4">
        <h2 class="text-xl font-semibold mb-4">Tóm tắt đơn hàng</h2>

        <!-- Product -->
        @php
            $totalPrice = 0;
        @endphp

        @foreach ($carts as $cart)
            @php
                $salePrice = $cart->sku->sale_price ?? $cart->combo->sale_price ?? 0;
                $totalPrice += $cart->quantity * $salePrice;
                $image = $cart->sku->images[0] ?? $cart->combo->images[0];
            @endphp

            <div class="flex items-start space-x-4 mb-4 pb-2 border-b border-b-neutral-300">
                <img src="{{ asset('storage/' . $image)}}" alt="Product Image" class="w-20 h-20 rounded-lg">
                <div class="flex-1">
                    <h3 class="font-medium line-clamp-2">
                        {{ $cart->sku->product->name ?? $cart->combo->combo_name }}</h3>
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
                        {{ number_format($cart->quantity * ($cart->sku ? $cart->sku->sale_price : $cart->combo->sale_price)) }} VNĐ
                    </p>
                </div>
            </div>

            <!-- Input ẩn cart_id để gửi lên controller -->
            <input type="hidden" name="cart_id[]" value="{{ $cart->id }}">
        @endforeach

        <!-- Promo Code -->
        <div class="mb-4 pb-4 border-b border-b-neutral-300">
            <div class="flex space-x-2">
                <button type="button"
                    class="w-full px-4 py-2 bg-blue-400 text-white rounded-lg transition-colors duration-300 hover:bg-black">Áp
                    voucher</button>
            </div>
        </div>

        <!-- Cost Breakdown -->
        <!-- Cost Breakdown -->
        <div class="space-y-2 mb-4 pb-4 border-b border-b-neutral-300">
            <div class="flex justify-between">
                <span class="text-gray-600">Tổng</span>
                <span>{{ number_format($totalPrice) }} VNĐ</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-600">Phí ship</span>
                <span class="text-green-600">Miễn phí</span>
            </div>
        </div>

        <!-- Total -->
        <div class="flex justify-between items-center mb-6">
            <span class="text-lg font-semibold">Tổng</span>
            <span class="text-lg font-semibold">{{ number_format($totalPrice) }} VNĐ</span>
        </div>


        <!-- Place Order Button -->
        {{-- wire:click="submitAddress" --}}
        @php
            $hasSelectedAddress = session()->has('selected_address_id');
        @endphp

        <button id="place-order-btn" type="submit" class="w-full bg-primary text-white py-4 rounded-full hover:bg-gray-800 flex items-center justify-center
{{ !$hasSelectedAddress ? 'opacity-50 cursor-not-allowed' : '' }}" {{ !$hasSelectedAddress ? 'disabled' : '' }}>
            <span>Đặt hàng</span>
            <i class="fas fa-lock ml-2"></i>
        </button>






        <!-- Additional Info -->
        <div class="mt-4 text-sm text-gray-600 text-center">
            <p>Thanh toán đồng nghĩa với việc bạn chấp nhận</p>
            <p><a href="#" class="underline">Điều khoản sử dụng</a> của chúng tôi</p>
        </div>
    </div>
</div>