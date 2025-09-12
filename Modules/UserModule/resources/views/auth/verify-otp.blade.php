<x-layouts.layout>
    <x-slot name="title">BeeBook - Xác thực OTP</x-slot>

    <main id="content" role="main" class="w-[1200px] mx-auto mb-6 rounded-[10px] bg-white py-10">
        <div class="bg-white w-1/2 p-5 mx-auto rounded-xl shadow-lg border-2 border-indigo-300">
            <div class="p-2 text-center">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 400 300"
                    class="mx-auto mb-6 w-40 h-40 animate-pulse" role="img" aria-label="OTP icon">
                    <circle cx="200" cy="200" r="150" fill="#3B82F6" />
                    <circle cx="200" cy="200" r="120" fill="#FFFFFF" />
                    <circle cx="200" cy="200" r="90" fill="#3B82F6" />
                    <circle cx="200" cy="200" r="60" fill="#FFFFFF" />
                    <text x="200" y="200" text-anchor="middle" fill="#2563EB" font-size="40" font-weight="bold"
                        dy=".3em" class="text-center select-none">OTP</text>
                </svg>

                <h2 class="text-2xl font-bold text-gray-800 dark:text-white mb-1">Xác thực OTP</h2>
                <p class="text-sm text-gray-600 dark:text-gray-300 mb-6">
                    Đã gửi mã xác thực gồm 6 số đến <strong>0987654321</strong>
                </p>

                <form action="" method="POST" class="mb-6">
                    @csrf
                    <div class="flex justify-center space-x-4">
                        @for ($i = 0; $i < 6; $i++)
                        <input 
                            type="text" 
                            name="otp[]" 
                            maxlength="1" 
                            inputmode="numeric"
                            pattern="[0-9]*"
                            required
                            class="w-12 h-16 text-center text-2xl border-2 border-blue-500 rounded-xl
                            focus:outline-none focus:ring-2 focus:ring-blue-500
                            transition-transform duration-300 hover:scale-110"
                        >
                        @endfor
                    </div>

                    @error('otp')
                        <p class="mt-2 text-red-600 text-center text-sm">{{ $message }}</p>
                    @enderror

                    <div class="text-sm text-gray-600 dark:text-gray-300 mt-4 text-center">
                        Chưa nhận được OTP? 
                        <button type="submit" formaction=""
                            class="text-blue-500 hover:underline dark:text-blue-400 transition-colors duration-300 hover:text-blue-600 dark:hover:text-blue-500 ml-1">
                            Gửi lại OTP
                        </button>
                    </div>

                    <button 
                        type="submit" 
                        class="mt-6 w-1/2 mx-auto block py-4 bg-blue-500 text-white rounded-xl hover:bg-blue-600
                        transition-transform duration-300 hover:scale-105
                        dark:bg-blue-600 dark:hover:bg-blue-700"
                    >
                        Xác thực OTP
                    </button>
                </form>

                <a href="{{ route('login') }}" class="inline-block mt-4 text-sm text-gray-600 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 transition-colors">
                    &larr; Quay lại đăng nhập
                </a>

            </div>
        </div>
    </main>
</x-layouts.layout>
