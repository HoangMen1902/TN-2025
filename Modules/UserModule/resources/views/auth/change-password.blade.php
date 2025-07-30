<x-layouts.layout>
    <div class="container mx-auto max-w-[1200px] my-3 md:my-6 flex flex-col md:flex md:flex-row md:h-[650px] px-4 md:px-0">

        <div class="w-full h-[500px] md:w-[300px] md:h-[500px] mb-4 md:mb-0">
            <x-usermodule::sidebar></x-usermodule::sidebar>
        </div>

        <div class="bg-white w-full md:w-[900px] py-4 md:py-8 rounded shadow-sm">
            <div class="mb-4 md:mb-6">
                <h1 class="text-xl md:text-2xl font-medium ml-4">Đổi mật khẩu</h1>
                <p class="text-gray-600 text-xs md:text-sm ml-4">Tăng cường bảo mật cho tài khoản của bạn!</p>
                <hr class="border-t border-gray-300 my-3 md:my-4 mx-4">
            </div>

            <div class="px-4">
                <form action="{{ route('password.change') }}" method="POST" class="max-w-xl">
                    @csrf

                    <div class="mb-4 flex flex-col md:flex-row md:items-center">
                        <label for="current_password" class="w-full md:w-1/3 text-gray-700 text-sm font-medium mb-1 md:mb-0 md:text-right md:pr-4">Mật khẩu hiện tại</label>
                        <div class="w-full md:w-2/3">
                            <input type="password" id="current_password" name="current_password" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-2 focus:ring-blue-300 focus:border-blue-500 outline-none" value="{{ old('current_password') }}">
                            @error('current_password')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-4 flex flex-col md:flex-row md:items-center">
                        <label for="password" class="w-full md:w-1/3 text-gray-700 text-sm font-medium mb-1 md:mb-0 md:text-right md:pr-4">Mật khẩu mới</label>
                        <div class="w-full md:w-2/3">
                            <input type="password" id="password" name="password" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-2 focus:ring-blue-300 focus:border-blue-500 outline-none">
                            @error('password')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-6 flex flex-col md:flex-row md:items-center">
                        <label for="password_confirmation" class="w-full md:w-1/3 text-gray-700 text-sm font-medium mb-1 md:mb-0 md:text-right md:pr-4">Xác nhận mật khẩu</label>
                        <div class="w-full md:w-2/3">
                            <input type="password" id="password_confirmation" name="password_confirmation" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-2 focus:ring-blue-300 focus:border-blue-500 outline-none">
                            @error('password_confirmation')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                    </div>

                    <div class="flex flex-col md:flex-row">
                        <div class="w-full md:w-1/3"></div>
                        <div class="w-full md:w-2/3">
                            <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-6 rounded-md transition-colors duration-200">Lưu Thay Đổi</button>
                        </div>
                    </div>
                </form>

            </div>

        </div>
    </div>
</x-layouts.layout>