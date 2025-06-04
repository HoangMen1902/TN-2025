<x-layouts.layout>
    <x-slot name="title">BeeBook - Đặt lại mật khẩu</x-slot>

    <div class="bg-gray-100 text-gray-900 flex mx-auto w-[1200px] justify-center">
        <div class="w-full m-0 mb-8 bg-white shadow sm:rounded-lg flex justify-center flex-1">
            <div class="lg:w-1/2 xl:w-5/12 p-6 sm:p-12">
                <div class="mt-12 flex flex-col items-center">
                    <h1 class="text-2xl xl:text-3xl font-bold text-[#2b4f60]">
                        Đặt lại mật khẩu
                    </h1>
                    <p class="text-sm text-gray-500 mt-2 mb-8 text-center">
                        Vui lòng nhập email và mật khẩu mới của bạn
                    </p>
                </div>

                <div class="w-full flex-1 mt-4">
                    <form action="{{ route('update-password') }}" method="POST" class="mx-auto max-w-xs" novalidate>
                        @csrf
                        <input type="hidden" name="token" value="{{ $token }}">

                        <input
                            type="email"
                            name="email"
                            placeholder="Email"
                            value="{{ old('email') }}"
                            required
                            class="w-full px-8 py-4 rounded-lg font-medium bg-gray-100 border border-gray-200 
                                   placeholder-gray-500 text-sm focus:outline-none focus:border-gray-400 focus:bg-white"
                        />
                        @error('email') 
                            <span class="error text-red-500 text-sm">{{ $message }}</span> 
                        @enderror

                        <input
                            type="password"
                            name="password"
                            placeholder="Mật khẩu mới"
                            required
                            class="w-full px-8 py-4 rounded-lg font-medium bg-gray-100 border border-gray-200 
                                   placeholder-gray-500 text-sm focus:outline-none focus:border-gray-400 focus:bg-white mt-5"
                        />
                        @error('password') 
                            <span class="error text-red-500 text-sm">{{ $message }}</span> 
                        @enderror

                        <input
                            type="password"
                            name="password_confirmation"
                            placeholder="Xác nhận mật khẩu"
                            required
                            class="w-full px-8 py-4 rounded-lg font-medium bg-gray-100 border border-gray-200 
                                   placeholder-gray-500 text-sm focus:outline-none focus:border-gray-400 focus:bg-white mt-5"
                        />
                        @error('password_confirmation') 
                            <span class="error text-red-500 text-sm">{{ $message }}</span> 
                        @enderror

                        <button
                            type="submit"
                            class="mt-5 tracking-wide font-semibold bg-indigo-500 text-gray-100 w-full py-4 rounded-lg 
                                   hover:bg-indigo-700 transition-all duration-300 ease-in-out flex items-center 
                                   justify-center focus:shadow-outline focus:outline-none"
                        >
                            <span class="ml-3 mr-3">
                                Đặt lại mật khẩu
                            </span> 
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                class="w-6 h-6 -ml-2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                            </svg>
                        </button>

                        <p class="mt-6 text-xs text-gray-600 text-center">
                            <a href="{{ route('login') }}" class="border-b border-gray-500 border-dotted text-indigo-600 hover:text-indigo-800">
                                Quay lại đăng nhập
                            </a>
                        </p>
                    </form>
                </div>
            </div>

            <div class="flex-1 bg-indigo-100 text-center hidden lg:flex">
                <div class="m-12 xl:m-16 w-full bg-contain bg-center bg-no-repeat"
                    style="background-image: url('https://storage.googleapis.com/devitary-image-host.appspot.com/15848031292911696601-undraw_designer_life_w96d.svg');">
                </div>
            </div>
        </div>
    </div>
</x-layouts.layout>
