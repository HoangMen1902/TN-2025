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
            <livewire:usermodule::components.reset-password />

            @if (session('status'))
            <div class="mb-4 p-4 text-green-700 bg-green-100 rounded-md text-center">
                {{ session('status') }}
            </div>
            @endif


        </div>
    </main>
</x-layouts.layout>