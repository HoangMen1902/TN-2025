<x-layouts.layout>
    <div class="container mx-auto max-w-[1200px] my-3 md:my-6 flex flex-col px-4 md:px-0">
        <div class="flex flex-col md:flex-row md:h-full w-full">
            <div class="w-full h-[500px] md:w-[300px] md:h-[500px] mb-4 md:mb-0">
                <x-usermodule::sidebar />
            </div>

            <div class="bg-white w-full md:w-[900px] py-4 md:py-8 rounded shadow-sm">
                <div class="mb-4 md:mb-6 flex justify-between items-center px-4">
                    <h1 class="text-lg md:text-2xl font-medium">Thông báo</h1>
                </div>

                @php
                    $tabs = [
                        'all' => 'Tất cả',
                        'order' => 'Đơn hàng',
                        'account' => 'Tài khoản',
                        'promotion' => 'Khuyến mãi',
                        'discount' => 'Ưu đãi độc quyền',
                    ];

                    $notifications = [
                        ['category' => 'Đơn hàng', 'title' => 'Đơn hàng #12345 đang được giao', 'content' => 'Nguyễn Hoài Bão bị khùng vãi, Nguyễn Hoài Bão bị khùng vãi,Nguyễn Hoài Bão bị khùng vãi,', 'time' => '15/05/2025 - 09:00'],
                        ['category' => 'Tài khoản', 'title' => 'Cập nhật mật khẩu thành công', 'content' => 'Nguyễn Hoài Bão bị khùng vãi, Nguyễn Hoài Bão bị khùng vãi,Nguyễn Hoài Bão bị khùng vãi,', 'time' => '14/05/2025 - 15:30'],
                        ['category' => 'Khuyến mãi', 'title' => 'Nhận voucher 50k cho đơn từ 199k', 'content' => 'Nguyễn Hoài Bão bị khùng vãi, Nguyễn Hoài Bão bị khùng vãi,Nguyễn Hoài Bão bị khùng vãi,', 'time' => '13/05/2025 - 10:00'],
                        ['category' => 'Ưu đãi độc quyền', 'title' => 'Ưu đãi VIP dành riêng cho bạn', 'content' => 'Nguyễn Hoài Bão bị khùng vãi, Nguyễn Hoài Bão bị khùng vãi,Nguyễn Hoài Bão bị khùng vãi,', 'time' => '12/05/2025 - 11:20'],
                        ['category' => 'Đơn hàng', 'title' => 'Đơn hàng #12346 đã giao thành công', 'content' => 'Nguyễn Hoài Bão bị khùng vãi, Nguyễn Hoài Bão bị khùng vãi,Nguyễn Hoài Bão bị khùng vãi,', 'time' => '11/05/2025 - 17:45'],
                    ];
                @endphp

                <hr class="border-t border-gray-300 my-2 md:my-4 mx-4">

                <div class="mb-4 border-b border-gray-200 relative">
                    <div class="flex overflow-x-auto scrollbar-hide" role="tablist">
                        <ul class="flex flex-nowrap whitespace-nowrap min-w-full">
                            @foreach($tabs as $id => $label)
                                <li class="mr-2" role="presentation">
                                    <button
                                        class="inline-block text-gray-500 p-4 border-b-2 border-transparent {{ $loop->first ? 'border-red-500 text-red-500' : 'hover:text-gray-600 hover:border-gray-300' }} rounded-t-lg"
                                        id="{{ $id }}-tab" data-tabs-target="#{{ $id }}" type="button" role="tab"
                                        aria-controls="{{ $id }}" aria-selected="{{ $loop->first ? 'true' : 'false' }}">
                                        {{ $label }}
                                    </button>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>

                <div id="notificationTabContent" class="p-4">
                    @foreach($tabs as $id => $label)
                        <div class="tab-content {{ !$loop->first ? 'hidden' : '' }}" id="{{ $id }}">
                            <div class="space-y-4">
                                @foreach ($notifications as $notification)
                                    @if ($id == 'all' || strtolower($notification['category']) == strtolower($label))
                                        <div class="bg-white border border-gray-200 rounded-lg p-4 shadow-sm">
                                            <div class="flex items-center mb-3">
                                                @if ($notification['category'] == 'Đơn hàng')
                                                    <div
                                                        class="w-8 h-8 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center mr-2">
                                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                d="M15.75 10.5V6a3.75 3.75 0 1 0-7.5 0v4.5m11.356-1.993 1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 0 1-1.12-1.243l1.264-12A1.125 1.125 0 0 1 5.513 7.5h12.974c.576 0 1.059.435 1.119 1.007ZM8.625 10.5a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm7.5 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
                                                        </svg>
                                                    </div>
                                                @elseif ($notification['category'] == 'Tài khoản')
                                                    <div
                                                        class="w-8 h-8 bg-green-100 text-green-600 rounded-full flex items-center justify-center mr-2">
                                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />

                                                        </svg>
                                                    </div>
                                                @elseif ($notification['category'] == 'Khuyến mãi')
                                                    <div
                                                        class="w-8 h-8 bg-yellow-100 text-yellow-600 rounded-full flex items-center justify-center mr-2">
                                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                d="M16.5 6v.75m0 3v.75m0 3v.75m0 3V18m-9-5.25h5.25M7.5 15h3M3.375 5.25c-.621 0-1.125.504-1.125 1.125v3.026a2.999 2.999 0 0 1 0 5.198v3.026c0 .621.504 1.125 1.125 1.125h17.25c.621 0 1.125-.504 1.125-1.125v-3.026a2.999 2.999 0 0 1 0-5.198V6.375c0-.621-.504-1.125-1.125-1.125H3.375Z" />
                                                        </svg>
                                                    </div>
                                                @elseif ($notification['category'] == 'Ưu đãi độc quyền')
                                                    <div
                                                        class="w-8 h-8 bg-purple-100 text-purple-600 rounded-full flex items-center justify-center mr-2">
                                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                d="M11.48 3.499a.562.562 0 0 1 1.04 0l2.125 5.111a.563.563 0 0 0 .475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 0 0-.182.557l1.285 5.385a.562.562 0 0 1-.84.61l-4.725-2.885a.562.562 0 0 0-.586 0L6.982 20.54a.562.562 0 0 1-.84-.61l1.285-5.386a.562.562 0 0 0-.182-.557l-4.204-3.602a.562.562 0 0 1 .321-.988l5.518-.442a.563.563 0 0 0 .475-.345L11.48 3.5Z" />
                                                        </svg>
                                                    </div>
                                                @endif

                                                <h2 class="text-sm font-semibold text-gray-800 uppercase">
                                                    {{ $notification['category'] }}
                                                </h2>
                                            </div>

                                            <hr class="border-gray-200 mb-3">

                                            <div class="flex items-start space-x-4">
                                                <div class="flex-shrink-0">
                                                    <img class="h-16 w-16 object-contain"
                                                        src="https://deo.shopeemobile.com/shopee/shopee-pcmall-live-sg/assets/12fe8880616de161.png"
                                                        alt="Thông báo">
                                                </div>
                                                <div>

                                                    <p class="text-sm font-bold text-gray-800 mb-1">
                                                        {{ $notification['title'] }}
                                                    </p>
                                                    <p class="text-sm font-medium text-gray-800 mb-1">
                                                        {{ $notification['content'] }}
                                                    </p>
                                                    <p class="text-xs text-gray-500">{{ $notification['time'] }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const tabButtons = document.querySelectorAll('[data-tabs-target]');
            const tabContents = document.querySelectorAll('#notificationTabContent > div');

            tabButtons.forEach(button => {
                button.addEventListener('click', () => {
                    const targetId = button.getAttribute('data-tabs-target').substring(1);

                    tabContents.forEach(content => content.classList.add('hidden'));

                    tabButtons.forEach(btn => {
                        btn.classList.remove('border-red-500', 'text-red-500');
                        btn.classList.add('border-transparent', 'hover:text-gray-600', 'hover:border-gray-300');
                    });

                    const targetContent = document.getElementById(targetId);
                    if (targetContent) targetContent.classList.remove('hidden');

                    button.classList.add('border-red-500', 'text-red-500');
                    button.classList.remove('border-transparent', 'hover:text-gray-600', 'hover:border-gray-300');
                });
            });
        });
    </script>
</x-layouts.layout>