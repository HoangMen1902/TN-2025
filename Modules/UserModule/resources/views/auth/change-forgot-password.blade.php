<x-layouts.layout>
    <x-slot name="title">BeeBook - Khôi phục mật khẩu</x-slot>
    <div class="flex items-center justify-center bg-white px-4 py-32">
        <div class="w-full max-w-md space-y-6">
            <div class="text-center">
                <h2 class="text-3xl font-bold text-[#2b4f60]">Đổi mật khẩu</h2>
                <p class="text-sm text-gray-500 mt-2">Vui lòng nhập mật khẩu mới của bạn</p>
            </div>
    
            <form wire:submit.prevent="changePassword" class="space-y-4">
                <input type="password" wire:model="new_password" class="w-full px-4 py-3 bg-gray-100 rounded-full focus:outline-none focus:ring-2 focus:ring-[#5cb8b2]" placeholder="Mật khẩu mới" required />
                <input type="password" wire:model="confirm_password" class="w-full px-4 py-3 bg-gray-100 rounded-full focus:outline-none focus:ring-2 focus:ring-[#5cb8b2]" placeholder="Xác nhận mật khẩu" required />
    
                <button type="submit" class="w-full py-3 bg-[#5cb8b2] text-white font-semibold rounded-full hover:bg-[#469a95] transition">Đổi mật khẩu</button>
            </form>
    
            <div class="text-center text-sm text-gray-600">
                <a href="{{ route('login') }}" class="text-[#5cb8b2] font-medium hover:underline">Đã có tài khoản? Đăng nhập</a>
            </div>
        </div>
    </div>
</x-layouts.layout>

