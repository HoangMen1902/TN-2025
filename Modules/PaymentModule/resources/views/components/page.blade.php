<main class="w-full max-w-[1200px] mx-auto px-4 py-8">
    <form method="POST" action="{{ route('checkout.store') }}">
        @csrf
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Checkout Form -->
            <div class="lg:col-span-2 space-y-8">

                <!-- Delivery Options -->
                <section class="bg-white p-6 rounded-lg shadow-sm">
                    <h2 class="text-xl font-semibold mb-4">Phương thức vận chuyển</h2>
                    <div class="space-y-4">
                        <label
                            class="flex items-center p-4 border border-neutral-300 rounded-lg cursor-pointer hover:border-black">
                            <input type="radio" name="shipment_unit" value="Giao Hàng Nhanh"
                                class="form-radio text-black" checked>
                            <div class="ml-4">
                                <div class="font-semibold text-sm">Giao tận nơi - Đơn vị Giao Hàng Nhanh</div>
                                <div class="text-sm text-gray-600">30.000đ • Nhận hàng từ 3 - 5 ngày</div>
                            </div>
                        </label>
                        <label
                            class="flex items-center p-4 border border-neutral-300 rounded-lg cursor-pointer hover:border-black">
                            <input type="radio" name="shipment_unit" value="Giao Hàng Tiết Kiệm"
                                class="form-radio text-black">
                            <div class="ml-4">
                                <div class="font-semibold text-sm">Giao tận nơi - Đơn vị Giao Hàng Tiết Kiệm</div>
                                <div class="text-sm text-gray-600">30.000đ • Nhận hàng từ 3 - 5 ngày</div>
                            </div>
                        </label>
                        <label
                            class="flex items-center p-4 border border-neutral-300 rounded-lg cursor-pointer hover:border-black">
                            <input type="radio" name="shipment_unit" value="NinjaVan" class="form-radio text-black">
                            <div class="ml-4">
                                <div class="font-semibold text-sm">Giao tận nơi - Đơn vị NinjaVan</div>
                                <div class="text-sm text-gray-600">30.000đ • Nhận hàng từ 3 - 5 ngày</div>
                            </div>
                        </label>
                    </div>
                </section>

                <!-- Shipping Address -->
                <section class="bg-white p-6 rounded-lg shadow-sm">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-xl font-semibold">Địa chỉ giao hàng</h2>
                    </div>

                    {{-- Hiển thị địa chỉ có sẵn --}}
                    {{-- <livewire:paymentmodule::components.pick-address /> --}}

                    {{-- Hiển thị form nhập địa chỉ mới --}}
                    <div class="mt-6 border-t pt-4">
                        <livewire:paymentmodule::components.address />
                    </div>
                </section>
                <!-- Payment Method -->
                <section class="bg-white p-6 rounded-lg shadow-sm">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-xl font-semibold">Phương thức thanh toán</h2>
                        <div class="flex space-x-2">
                            <img src="https://upload.wikimedia.org/wikipedia/commons/5/5e/Visa_Inc._logo.svg" alt="Visa"
                                class="h-6">
                            <img src="https://upload.wikimedia.org/wikipedia/commons/2/2a/Mastercard-logo.svg"
                                alt="Mastercard" class="h-6">
                            <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/f/fa/American_Express_logo_%282018%29.svg/2052px-American_Express_logo_%282018%29.svg.png"
                                alt="Amex" class="h-6">
                        </div>
                    </div>
                    <div class="space-y-4">
                        <label
                            class="flex items-center p-3 border rounded-lg cursor-pointer hover:border-black border-neutral-300">
                            <input type="radio" name="payment_method" value="vnpay" class="form-radio text-black">
                            <div class="ml-4">
                                <div class="font-semibold text-sm">Chuyển khoản - VNPay</div>
                            </div>
                        </label>
                        <label
                            class="flex items-center p-3 border rounded-lg cursor-pointer hover:border-black border-neutral-300">
                            <input type="radio" name="payment_method" value="international"
                                class="form-radio text-black">
                            <div class="ml-4">
                                <div class="font-semibold text-sm">Thanh toán quốc tế - Visa/Mastercard</div>
                            </div>
                        </label>
                        <label
                            class="flex items-center p-3 border rounded-lg cursor-pointer hover:border-black border-neutral-300">
                            <input type="radio" name="payment_method" value="cod" class="form-radio text-black" checked>
                            <div class="ml-4">
                                <div class="font-semibold text-sm">Tiền mặt khi nhận hàng</div>
                            </div>
                        </label>
                    </div>
                </section>
            </div>

            <!-- Order Summary -->
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
        </div>
    </form>
</main>