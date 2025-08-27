<x-layouts.layout>
    <main class="w-full max-w-[1200px] mx-auto px-4 py-8">
        <form method="POST" action="{{ route('ebook.checkout.store') }}">
            @csrf
            <input type="hidden" name="ebook_id" value="{{ $ebook->id }}">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <div class="lg:col-span-3">
                    <div class="bg-white p-6 rounded-lg shadow-sm sticky top-4">
                        <h2 class="text-xl font-semibold mb-4">Thanh toán ebook</h2>

                        <div class="flex items-start space-x-4 mb-4 pb-2 border-b border-b-neutral-300">
                            <img src="{{ asset('storage/' . ($ebook->cover_image ?? 'default-image.jpg')) }}" alt="Ebook Image" class="w-20 h-20 rounded-lg">
                            <div class="flex-1">
                                <h3 class="font-medium line-clamp-2">{{ $ebook->title }}</h3>
                                <p class="text-sm text-gray-600">Số lượng: 1</p>
                                <p class="font-medium text-sm text-red-600 mt-1">Tổng: {{ number_format($ebook->price) }}<span class="text-sm">đ</span></p>
                            </div>
                        </div>

                        <div class="space-y-2 mb-4 pb-4 border-b border-b-neutral-300">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Tổng</span>
                                <span>{{ number_format($ebook->price) }}<span class="text-sm">đ</span></span>
                            </div>
                        </div>

                        <div class="flex justify-between items-center mb-6">
                            <span class="text-lg font-semibold">Tổng thanh toán</span>
                            <span class="text-lg font-semibold">{{ number_format($ebook->price) }}<span class="text-sm">đ</span></span>
                        </div>

                        <section class="bg-white p-6 rounded-lg shadow-sm mb-6">
                            <h2 class="text-xl font-semibold mb-4">Phương thức thanh toán</h2>
                            <div class="space-y-4">
                                {{-- <label class="flex items-center p-3 border rounded-lg cursor-pointer hover:border-black border-neutral-300">
                                    <input type="radio" name="payment_method" value="vnpay" class="form-radio text-black" required>
                                    <div class="ml-4">
                                        <div class="font-semibold text-sm">Chuyển khoản - VNPay</div>
                                    </div>
                                </label> --}}
                                <label class="flex items-center p-3 border rounded-lg cursor-pointer hover:border-black border-neutral-300">
                                    <input type="radio" name="payment_method" value="international" class="form-radio text-black" required>
                                    <div class="ml-4">
                                        <div class="font-semibold text-sm">Thanh toán quốc tế - Visa/Mastercard</div>
                                    </div>
                                </label>
                                {{-- <label class="flex items-center p-3 border rounded-lg cursor-pointer hover:border-black border-neutral-300">
                                    <input type="radio" name="payment_method" value="payos" class="form-radio text-black" required>
                                    <div class="ml-4">
                                        <div class="font-semibold text-sm">VietQR</div>
                                    </div>
                                </label> --}}
                            </div>
                        </section>

                        <button type="submit" class="w-full bg-blue-500 text-white py-4 rounded-full hover:bg-blue-600 transition duration-300">
                            Thanh toán ngay
                        </button>

                        <div class="mt-4 text-sm text-gray-600 text-center">
                            <p>Thanh toán đồng nghĩa với việc bạn chấp nhận</p>
                            <p><a href="#" class="underline">Điều khoản sử dụng</a> của chúng tôi</p>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </main>
</x-layouts.layout>