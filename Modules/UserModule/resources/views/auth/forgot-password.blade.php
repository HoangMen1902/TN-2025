<x-layouts.layout>
    <x-slot name="title">BeeBook - Khôi phục mật khẩu</x-slot>
    <div class="flex items-center justify-center bg-white px-4 py-32">
        <div class="w-full max-w-md space-y-6">
            <div class="text-center">
                <h2 class="text-2xl font-bold text-[#2b4f60]">Khôi phục mật khẩu</h2>
                <p class="text-sm text-gray-500 mt-2">Nhập email của bạn và nhận mã xác minh để khôi phục mật khẩu</p>
            </div>
            <livewire:usermodule::components.reset-password />

            <div class="text-center text-sm text-gray-600 mt-4">
                Nhớ mật khẩu?
                <a href="{{ route('login') }}" class="text-[#5cb8b2] font-medium hover:underline">Đăng nhập</a>
            </div>
        </div>
    </div>
</x-layouts.layout>