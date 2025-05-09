<x-layouts.layout>
    <x-slot name="title">BeeBook - Khôi phục mật khẩu</x-slot>
    <div class="flex items-center justify-center bg-white px-4 py-32">
        <div class="w-full max-w-md space-y-6">
            <div class="text-center">
                <h2 class="text-2xl font-bold text-[#2b4f60]">Khôi phục mật khẩu</h2>
                <p class="text-sm text-gray-500 mt-2">Nhập email của bạn và nhận mã xác minh để khôi phục mật khẩu</p>
            </div>
    
            <form  wire:submit.prevent="sendResetCode" class="space-y-4">
                <div class="flex items-center space-x-2">
                    <input type="email" wire:model="email" class="w-full px-4 py-3 bg-gray-100 rounded-full focus:outline-none focus:ring-2 focus:ring-[#5cb8b2]" placeholder="Email của bạn" required />
                    <button type="submit" class="py-3 px-3 w-1/3 bg-[#5cb8b2] text-white rounded-full hover:bg-[#469a95] transition text-sm text-center">Gửi mã</button>
    
                </div>
    
                <div class="mt-4">
                    <input type="text" wire:model="verification_code" maxlength="6" class="w-full px-4 py-3 text-center tracking-widest uppercase bg-gray-100 rounded-full focus:outline-none focus:ring-2 focus:ring-[#5cb8b2]" placeholder="Nhập mã xác minh" required />
                </div>
    
                <button onclick="window.location.href='change-forgot-password'" type="submit" class="w-full py-3 bg-[#5cb8b2] text-white font-semibold rounded-full hover:bg-[#469a95] transition">Xác minh mã</button>
            </form>
    
            <div class="text-center text-sm text-gray-600 mt-4">
                Nhớ mật khẩu?
                <a href="{{ route('login') }}" class="text-[#5cb8b2] font-medium hover:underline">Đăng nhập</a>
            </div>
        </div>
    </div>
</x-layouts.layout>


