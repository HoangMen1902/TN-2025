<x-layouts.layout>
    <x-slot name="title">BeeBook - Khôi phục mật khẩu</x-slot>

    <main id="content" role="main" class="max-w-[1200px] mx-auto my-24 rounded-lg bg-white py-20 px-6 shadow-sm">
        <div class="max-w-md mx-auto">
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold text-gray-800 dark:text-gray-700">Khôi phục mật khẩu?</h1>
                <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                    Đã nhớ mật khẩu? 
                    <a href="{{ route('login') }}" class="text-blue-600 font-medium hover:underline decoration-2">
                        Đăng nhập
                    </a>
                </p>
            </div>

            @if (session('status'))
                <div class="mb-4 p-4 text-green-700 bg-green-100 rounded-md text-center">
                    {{ session('status') }}
                </div>
            @endif

            <form action="{{ route('verify-otp') }}" method="POST" novalidate>
                @csrf
                <div class="mb-6">
                    <label for="email" class="block mb-2 text-sm font-semibold text-gray-700 dark:text-gray-800">Nhập email của bạn</label>
                    <input 
                        type="email" 
                        id="email" 
                        name="email" 
                        value="{{ old('email') }}" 
                        required
                        autofocus
                        placeholder="you@example.com"
                        class="w-full rounded-xl border border-gray-300 px-4 py-3 text-sm placeholder-gray-400 
                            focus:border-blue-500 focus:ring-1 focus:ring-blue-500 shadow-sm
                            @error('email') border-red-500 focus:border-red-500 focus:ring-red-500 @enderror"
                        aria-describedby="email-error"
                    >
                    @error('email')
                        <p class="mt-2 text-red-600 text-sm" id="email-error">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit" 
                    class="w-full py-4 rounded-xl bg-blue-600 text-white font-semibold hover:bg-blue-700 
                    focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition 
                    duration-300">
                    Gửi liên kết khôi phục
                </button>
            </form>
        </div>
    </main>
</x-layouts.layout>
