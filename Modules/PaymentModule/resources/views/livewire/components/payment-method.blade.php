<!-- Payment Method -->
<section class="bg-white p-6 rounded-lg shadow-sm">
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-xl font-semibold">Phương thức thanh toán</h2>
        <div class="flex space-x-2">
            <img src="https://upload.wikimedia.org/wikipedia/commons/5/5e/Visa_Inc._logo.svg" alt="Visa" class="h-6">
            <img src="https://upload.wikimedia.org/wikipedia/commons/2/2a/Mastercard-logo.svg" alt="Mastercard"
                class="h-6">
            <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/f/fa/American_Express_logo_%282018%29.svg/2052px-American_Express_logo_%282018%29.svg.png"
                alt="Amex" class="h-6">
        </div>
    </div>
    <div class="space-y-4">
        <label class="flex items-center p-3 border rounded-lg cursor-pointer hover:border-black border-neutral-300">
            <input type="radio" name="payment_method" value="vnpay" class="form-radio text-black">
            <div class="ml-4">
                <div class="font-semibold text-sm">Chuyển khoản - VNPay</div>
            </div>
        </label>
        <label class="flex items-center p-3 border rounded-lg cursor-pointer hover:border-black border-neutral-300">
            <input type="radio" name="payment_method" value="international" class="form-radio text-black">
            <div class="ml-4">
                <div class="font-semibold text-sm">Thanh toán quốc tế - Visa/Mastercard</div>
            </div>
        </label>
        <label class="flex items-center p-3 border rounded-lg cursor-pointer hover:border-black border-neutral-300">
            <input type="radio" name="payment_method" value="payos" class="form-radio text-black">
            <div class="ml-4">
                <div class="font-semibold text-sm">VietQR</div>
            </div>
        </label>
        <label class="flex items-center p-3 border rounded-lg cursor-pointer hover:border-black border-neutral-300">
            <input type="radio" name="payment_method" value="cod" class="form-radio text-black" checked>
            <div class="ml-4">
                <div class="font-semibold text-sm">Tiền mặt khi nhận hàng</div>
            </div>
        </label>
    </div>
</section>