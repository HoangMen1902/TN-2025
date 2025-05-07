@extends('usermodule::layouts.app')
@section('title')
Đăng nhập
@endsection
@section('content')
<div class="min-h-screen flex items-center justify-center bg-white px-4 py-12">
    <div class="w-full max-w-md space-y-6">
        <div class="text-center">
            <h2 class="text-3xl font-bold text-[#2b4f60]">Đăng nhập</h2>
            <p class="text-sm text-gray-500 mt-2">Chào mừng đến với BeeBook - Thế giới sách</p>
        </div>

        <form wire:submit.prevent="login" class="space-y-4">
            <input type="email" wire:model="email" class="w-full px-4 py-3 bg-gray-100 rounded-full focus:outline-none focus:ring-2 focus:ring-[#5cb8b2]" placeholder="Email" required />
            <input type="password" wire:model="password" class="w-full px-4 py-3 bg-gray-100 rounded-full focus:outline-none focus:ring-2 focus:ring-[#5cb8b2]" placeholder="Mật khẩu" required />

            <div class="text-right text-sm">
                <a href="{{ route('forgot-password') }}" class="text-[#5cb8b2] hover:underline">Quên mật khẩu?</a>
            </div>

            <button type="submit" class="w-full py-3 bg-[#5cb8b2] text-white font-semibold rounded-full hover:bg-[#469a95] transition">Đăng nhập</button>
        </form>

        <div class="flex items-center justify-center gap-4 mt-4">
            <a href="" class="w-12 h-12 bg-red-500 rounded-full flex items-center justify-center hover:bg-red-600 transition" title="Google">
                <svg class="w-5 h-5 text-white" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M21.35 11.1H12v2.8h5.4c-.6 2.5-2.8 4.2-5.4 4.2-3.2 0-5.8-2.6-5.8-5.8s2.6-5.8 5.8-5.8c1.5 0 2.9.6 3.9 1.6l2-2C17.7 3.8 15 2.5 12 2.5 6.9 2.5 2.5 6.9 2.5 12s4.4 9.5 9.5 9.5c4.9 0 9-4 9-9 0-.6-.1-1.2-.2-1.4z"/>
                </svg>
            </a>
            <a href="" class="w-12 h-12 bg-blue-600 rounded-full flex items-center justify-center hover:bg-blue-700 transition" title="Facebook">
                <svg class="w-5 h-5 text-white" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M22 12c0-5.5-4.5-10-10-10S2 6.5 2 12c0 5 3.7 9.1 8.5 9.9v-7H8v-3h2.5V9.5c0-2.5 1.5-3.9 3.7-3.9 1.1 0 2.2.2 2.2.2v2.4h-1.3c-1.3 0-1.7.8-1.7 1.6V12H17l-.5 3h-2v7c4.8-.8 8.5-4.9 8.5-9.9z"/>
                </svg>
            </a>
        </div>

        <div class="text-center text-sm text-gray-600">
            Chưa có tài khoản?
            <a href="{{route ('register')}}" class="text-[#5cb8b2] font-medium hover:underline">Đăng ký ngay</a>
        </div>
    </div>
</div>
@endsection
