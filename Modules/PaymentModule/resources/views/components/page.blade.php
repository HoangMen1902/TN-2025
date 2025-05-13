    <!-- Header with Progress Indicator -->
    
    <main class="w-full max-w-[1200px] mx-auto px-4 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Checkout Form -->
            <div class="lg:col-span-2 space-y-8">
                <!-- Delivery Options -->
                <section class="bg-white p-6 rounded-lg shadow-sm">
                    <h2 class="text-xl font-semibold mb-4">Phương thức vận chuyển</h2>
                    <div class="space-y-4">
                        <label class="flex items-center p-4 border border-neutral-300 rounded-lg cursor-pointer hover:border-black">
                            <input type="radio" name="delivery" class="form-radio text-black" checked>
                            <div class="ml-4">
                                <div class="font-semibold text-sm">Giao tận nơi - Đơn vị Giao Hàng Nhanh</div>
                                <div class="text-sm text-gray-600">30.000đ • Nhận hàng từ 3 - 5 ngày</div>
                            </div>
                        </label>
                        <label class="flex items-center p-4 border border-neutral-300 rounded-lg cursor-pointer hover:border-black">
                            <input type="radio" name="delivery" class="form-radio text-black" checked>
                            <div class="ml-4">
                                <div class="font-semibold text-sm">Giao tận nơi - Đơn vị Giao Hàng Tiết Kiệm</div>
                                <div class="text-sm text-gray-600">30.000đ • Nhận hàng từ 3 - 5 ngày</div>
                            </div>
                        </label>
                        <label class="flex items-center p-4 border border-neutral-300 rounded-lg cursor-pointer hover:border-black">
                            <input type="radio" name="delivery" class="form-radio text-black" checked>
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
                        <button class="text-sm text-gray-600 hover:text-black">
                            <i class="far fa-address-book mr-1"></i> Dùng địa chỉ có sẵn
                        </button>
                    </div>
                    <form class="space-y-4">
                        <div class="">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Họ và tên</label>
                                <input type="text" class="w-full p-2 border rounded-lg focus:ring-2 focus:ring-black focus:outline-none" placeholder="Ex: Nguyễn Văn A, ...">
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Địa chỉ chi tiết</label>
                            <input type="text" class="w-full p-2 border rounded-lg focus:ring-2 focus:ring-black focus:outline-none" placeholder="123 Nguyễn Văn Linh, ...">
                        </div>

                        <livewire:paymentmodule::components.address>
                        </livewire:paymentmodule::components.address>
                    </form>
                </section>

                <!-- Payment Method -->
                <section class="bg-white p-6 rounded-lg shadow-sm">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-xl font-semibold">Phương thức thanh toán</h2>
                        <div class="flex space-x-2">
                            <img src="https://upload.wikimedia.org/wikipedia/commons/5/5e/Visa_Inc._logo.svg" alt="Visa" class="h-6">
                            <img src="https://upload.wikimedia.org/wikipedia/commons/2/2a/Mastercard-logo.svg" alt="Mastercard" class="h-6">
                            <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/f/fa/American_Express_logo_%282018%29.svg/2052px-American_Express_logo_%282018%29.svg.png" alt="Amex" class="h-6">
                        </div>
                        
                    </div>
                    <div class="space-y-4">
                        <label class="flex items-center p-3 border rounded-lg cursor-pointer hover:border-black border-neutral-300">
                            <input type="radio" name="payment-method" value="vnpay" class="form-radio text-black" checked>
                            <div class="ml-4">
                                <div class="font-semibold text-sm">Chuyển khoản - VNPay</div>
                            </div>
                        </label>
                        <label class="flex items-center p-3 border rounded-lg cursor-pointer hover:border-black border-neutral-300">
                            <input type="radio" name="payment-method" value="international" class="form-radio text-black" checked>
                            <div class="ml-4">
                                <div class="font-semibold text-sm">Thanh toán quốc tế - Visa/Mastercard</div>
                            </div>
                        </label>
                        <label class="flex items-center p-3 border rounded-lg cursor-pointer hover:border-black border-neutral-300">
                            <input type="radio" name="payment-method" value="cod" class="form-radio text-black" checked>
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
                    <div class="flex items-start space-x-4 mb-4 pb-4 border-b">
                        <img src="https://images.unsplash.com/photo-1515955656352-a1fa3ffcd111?q=80&w=2070&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D" alt="Nike Air Max 2024" class="w-20 h-20 rounded-lg">
                        <div class="flex-1">
                            <h3 class="font-medium">Nike Air Max 2024</h3>
                            <p class="text-sm text-gray-600">Size: US 10</p>
                            <p class="text-sm text-gray-600">Quantity: 1</p>
                            <p class="font-medium mt-1">$179.99</p>
                        </div>
                    </div>

                    <!-- Promo Code -->
                    <div class="mb-4 pb-4 border-b">
                        <div class="flex space-x-2">
                            <button type="button" class=" w-full px-4 py-2 bg-gray-900 text-white rounded-lg hover:bg-black">Áp voucher</button>
                        </div>
                    </div>

                    <!-- Cost Breakdown -->
                    <div class="space-y-2 mb-4 pb-4 border-b">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Tổng</span>
                            <span>100.000 VNĐ</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Phí ship</span>
                            <span class="text-green-600">Miễn phí</span>
                        </div>

                    </div>

                    <!-- Total -->
                    <div class="flex justify-between items-center mb-6">
                        <span class="text-lg font-semibold">Tổng</span>
                        <span class="text-lg font-semibold">100.000 VNĐ</span>
                    </div>

                    <!-- Place Order Button -->
                    <button class="w-full bg-primary text-white py-4 rounded-full hover:bg-gray-800 flex items-center justify-center">
                        <span>Đặt hàng</span>
                        <i class="fas fa-lock ml-2"></i>
                    </button>

                    <!-- Additional Info -->
                    <div class="mt-4 text-sm text-gray-600 text-center">
                        <p>Thanh toán đồng nghĩa với việc bạn chấp nhận</p>
                        <p><a href="#" class="underline">Điều khoản sử dụng</a> Của chúng tôi</p>
                    </div>
                </div>
            </div>
        </div>
    </main>

  