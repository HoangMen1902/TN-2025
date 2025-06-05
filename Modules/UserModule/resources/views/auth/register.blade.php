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
                                    <svg class="w-4" viewBox="0 0 533.5 544.3">
                                        <path fill="#4285f4" d="M533.5 278.4c0-18.5..."/>
                                        <path fill="#34a853" d="M272.1 544.3c73.4 0..."/>
                                        <path fill="#fbbc04" d="M119.3 324.3c-11.4..."/>
                                        <path fill="#ea4335" d="M272.1 107.7c38.8..."/>
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
                                @error('password_confirmation') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror

                                <button type="submit"
                                        class="mt-5 tracking-wide font-semibold bg-indigo-500 text-white w-full py-4 rounded-lg hover:bg-indigo-700 transition duration-300 flex items-center justify-center">
                                    <span class="ml-3 mr-3">Đăng ký</span>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 -ml-2" fill="none"
                                         viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                              d="M13.5 4.5L21 12m0 0-7.5 7.5M21 12H3"/>
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

            <div class="hidden lg:flex lg:w-1/2 bg-indigo-100 items-center justify-center">
                <div class="m-12 xl:m-16 w-full bg-contain bg-center bg-no-repeat"
                     style="background-image: url('https://storage.googleapis.com/devitary-image-host.appspot.com/15848031292911696601-undraw_designer_life_w96d.svg');">
                </div>
            </div>
        </div>
    </div>
</x-layouts.layout>
