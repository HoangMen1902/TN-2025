<x-layouts.layout>
    <x-slot name="title">BeeBook - Đặt lại mật khẩu</x-slot>
    <div class="flex items-center justify-center py-32 px-4 bg-white">
        <div class="w-full max-w-md space-y-6">
            <div class="text-center">
                <h2 class="text-3xl font-bold text-[#2b4f60]">Đặt lại mật khẩu</h2>
                <p class="text-sm text-gray-500 mt-2">Nhập email và mật khẩu mới của bạn</p>
            </div>

            <form action="{{ route('update-password') }}" method="POST" class="space-y-4">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">

                <input class="w-full px-4 py-3 bg-gray-100 rounded-full" type="email" name="email" placeholder="Email"  value="{{ old('email') }}">
                @error('email')
                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror

                <input class="w-full px-4 py-3 bg-gray-100 rounded-full" type="password" name="password" placeholder="Mật khẩu mới" >
                @error('password')
                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror

                <input class="w-full px-4 py-3 bg-gray-100 rounded-full" type="password" name="password_confirmation" placeholder="Xác nhận mật khẩu" >
                @error('password_confirmation')
                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror

                <button type="submit" class="w-full py-3 bg-[#5cb8b2] text-white font-semibold rounded-full hover:bg-[#469a95] transition">
                    Lưu mật khẩu
                </button>
            </form>


            <div class="text-center text-sm text-gray-600">
                <a href="{{ route('login') }}" class="text-[#5cb8b2] font-medium hover:underline">Quay lại đăng nhập</a>
            </div>
        </div>
    </div>
</x-layouts.layout>