<x-layouts.layout>
    <x-slot name="title">BeeBook - Đổi Mật Khẩu</x-slot>

    <div class="bg-gray-100 text-gray-900 flex mx-auto w-full max-w-[1200px] justify-center min-h-[600px] px-4 md:px-0">
        <div class="w-full m-0 mb-8 bg-white shadow sm:rounded-lg flex flex-col md:flex-row justify-center flex-1">
            <div class="w-full md:w-1/2 xl:w-5/12 p-6 sm:p-12">
                <div class="mt-12 flex flex-col items-center">
                    <h1 class="text-2xl xl:text-3xl font-bold text-[#2b4f60]">
                        Đổi mật khẩu
                    </h1>
                    <p class="text-sm text-gray-500 mt-2 mb-6 text-center">
                        Vui lòng nhập mật khẩu cũ và mật khẩu mới để tiếp tục.
                    </p>
                    <div class="w-full flex-1">

                        @if(session('status'))
                            <p class="text-green-500 text-sm mb-4">{{ session('status') }}</p>
                        @endif

                        <form method="POST" action="{{ route('password.change') }}" class="mx-auto max-w-xs space-y-5">
                            @csrf

                            <input
                                type="password"
                                name="current_password"
                                placeholder="Mật khẩu cũ"
                                class="w-full px-8 py-4 rounded-lg font-medium bg-gray-100 border border-gray-200 placeholder-gray-500 text-sm focus:outline-none focus:border-gray-400 focus:bg-white"
                                value="{{ old('current_password') }}"
                            />
                            @error('current_password')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror

                            <input
                                type="password"
                                name="password"
                                placeholder="Mật khẩu mới"
                                class="w-full px-8 py-4 rounded-lg font-medium bg-gray-100 border border-gray-200 placeholder-gray-500 text-sm focus:outline-none focus:border-gray-400 focus:bg-white"
                            />
                            @error('password')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror

                            <input
                                type="password"
                                name="password_confirmation"
                                placeholder="Xác nhận mật khẩu mới"
                                class="w-full px-8 py-4 rounded-lg font-medium bg-gray-100 border border-gray-200 placeholder-gray-500 text-sm focus:outline-none focus:border-gray-400 focus:bg-white"
                            />
                            @error('password_confirmation')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror

                            <button
                                type="submit"
                                class="mt-5 tracking-wide font-semibold bg-indigo-500 text-gray-100 w-full py-4 rounded-lg hover:bg-indigo-700 transition-all duration-300 ease-in-out flex items-center justify-center focus:shadow-outline focus:outline-none">
                                <span class="ml-3 mr-3">
                                    Đổi mật khẩu
                                </span>
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                    viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                    class="w-6 h-6 -ml-2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                                </svg>
                            </button>
                        </form>

                    </div>
                </div>
            </div>

            <div class="hidden md:flex flex-1 bg-indigo-100 text-center">
                <div class="m-12 xl:m-16 w-full bg-contain bg-center bg-no-repeat"
                    style="background-image: url('https://storage.googleapis.com/devitary-image-host.appspot.com/15848031292911696601-undraw_designer_life_w96d.svg');">
                </div>
            </div>
        </div>
    </div>
</x-layouts.layout>
