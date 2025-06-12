<x-layouts.layout>
    <x-slot name="title">BeeBook - Đăng Nhập</x-slot>

    @if (session('success'))
        <div class="text-green-600 text-center font-semibold">{{ session('success') }}</div>
    @endif

    <div class="bg-gray-100 text-gray-900 flex justify-center px-4 py-10">
        <div class="w-full max-w-[1200px] bg-white shadow sm:rounded-lg flex flex-col lg:flex-row overflow-hidden">
            <div class="w-full lg:w-1/2 p-6 sm:p-12">
                <div class="mt-4 flex flex-col items-center">
                    <h1 class="text-2xl xl:text-3xl font-bold">Đăng ký</h1>

                    <div class="w-full flex-1 mt-4">
                        <div class="flex flex-col items-center">
                            <a href="{{ route('login.google') }}"
                                class="w-full max-w-xs font-bold shadow-sm rounded-lg py-3 bg-indigo-100 text-gray-800 flex items-center justify-center transition-all duration-300 hover:shadow">
                                <div class="bg-white p-2 rounded-full">
                                    <svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="35" height="35"
                                        viewBox="0 0 48 48">
                                        <path fill="#FFC107"
                                            d="M43.611,20.083H42V20H24v8h11.303c-1.649,4.657-6.08,8-11.303,8c-6.627,0-12-5.373-12-12c0-6.627,5.373-12,12-12c3.059,0,5.842,1.154,7.961,3.039l5.657-5.657C34.046,6.053,29.268,4,24,4C12.955,4,4,12.955,4,24c0,11.045,8.955,20,20,20c11.045,0,20-8.955,20-20C44,22.659,43.862,21.35,43.611,20.083z">
                                        </path>
                                        <path fill="#FF3D00"
                                            d="M6.306,14.691l6.571,4.819C14.655,15.108,18.961,12,24,12c3.059,0,5.842,1.154,7.961,3.039l5.657-5.657C34.046,6.053,29.268,4,24,4C16.318,4,9.656,8.337,6.306,14.691z">
                                        </path>
                                        <path fill="#4CAF50"
                                            d="M24,44c5.166,0,9.86-1.977,13.409-5.192l-6.19-5.238C29.211,35.091,26.715,36,24,36c-5.202,0-9.619-3.317-11.283-7.946l-6.522,5.025C9.505,39.556,16.227,44,24,44z">
                                        </path>
                                        <path fill="#1976D2"
                                            d="M43.611,20.083H42V20H24v8h11.303c-0.792,2.237-2.231,4.166-4.087,5.571c0.001-0.001,0.002-0.001,0.003-0.002l6.19,5.238C36.971,39.205,44,34,44,24C44,22.659,43.862,21.35,43.611,20.083z">
                                        </path>
                                    </svg>
                                </div>
                                <span class="ml-4">Đăng nhập với Google</span>
                            </a>
                        </div>

                        <div class="my-10 border-b text-center">
                            <div class="inline-block px-2 text-sm text-gray-600 bg-white transform translate-y-1/2">
                                Hoặc đăng ký tài khoản BeeBook
                            </div>
                        </div>

                        <form method="POST" action="{{ route('register') }}">
                            @csrf
                            <div class="mx-auto max-w-xs space-y-4">
                                <input name="name" type="text" placeholder="Họ và tên"
                                    class="w-full px-8 py-4 rounded-lg bg-gray-100 border border-gray-200 placeholder-gray-500 text-sm focus:outline-none focus:border-gray-400 focus:bg-white" />
                                @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror

                                <input name="email" type="email" placeholder="Email" value="{{ old('email') }}"
                                    class="w-full px-8 py-4 rounded-lg bg-gray-100 border border-gray-200 placeholder-gray-500 text-sm focus:outline-none focus:border-gray-400 focus:bg-white" />
                                @error('email') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror

                                <input name="password" type="password" placeholder="Mật khẩu"
                                    class="w-full px-8 py-4 rounded-lg bg-gray-100 border border-gray-200 placeholder-gray-500 text-sm focus:outline-none focus:border-gray-400 focus:bg-white" />
                                @error('password') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror

                                <input name="password_confirmation" type="password" placeholder="Xác nhận mật khẩu"
                                    class="w-full px-8 py-4 rounded-lg bg-gray-100 border border-gray-200 placeholder-gray-500 text-sm focus:outline-none focus:border-gray-400 focus:bg-white" />
                                @error('password_confirmation') <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror

                                <button type="submit"
                                    class="mt-5 tracking-wide font-semibold bg-indigo-500 text-white w-full py-4 rounded-lg hover:bg-indigo-700 transition duration-300 flex items-center justify-center">
                                    <span class="ml-3 mr-3">Đăng ký</span>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 -ml-2" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M13.5 4.5L21 12m0 0-7.5 7.5M21 12H3" />
                                    </svg>
                                </button>

                                <p class="mt-6 text-xs text-gray-600 text-center">
                                    Đã có tài khoản?
                                    <a href="{{ route('login') }}" class="border-b border-gray-500 border-dotted">
                                        Đăng nhập
                                    </a>
                                </p>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="hidden lg:flex lg:w-1/2 bg-indigo-100 items-stretch">
                <div class="w-full h-full bg-center bg-no-repeat"
                    style="background-size: 100% 100%; background-image: url('{{ asset('assets/images/login.jpg') }}');">
                </div>
            </div>
        </div>
    </div>
</x-layouts.layout>