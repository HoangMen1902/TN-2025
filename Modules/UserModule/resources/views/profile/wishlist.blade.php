<x-layouts.layout>
    <div class="container mx-auto max-w-[1200px] my-3 md:my-6 flex flex-col px-4 md:px-0">
        <div class="flex flex-col md:flex-row md:h-full w-full">
            <div class="w-full h-[500px] md:w-[300px] md:h-[500px] mb-4 md:mb-0">
                <x-usermodule::sidebar />
            </div>

            <div class="bg-white w-full md:w-[900px] py-4 md:py-8 rounded shadow-sm">
                <div class="mb-4 md:mb-6 flex justify-between items-center px-4">
                    <h1 class="text-lg md:text-2xl font-medium">Sản phẩm yêu thích</h1>
                </div>

                @php
                    $tabs = [
                        'all' => 'Tất cả',
                    ];

                    $products = [
                        [
                            'id' => 1,
                            'category' => 'Tiểu thuyết',
                            'name' => 'Nhật Ký Đặng Thùy Trâm (Tái Bản 2022)',
                            'price' => '120.000 ₫',
                            'image' => 'https://via.placeholder.com/150',
                            'time' => '15/05/2025 - 09:00'
                        ],
                        [
                            'id' => 2,
                            'category' => 'Phát triển bản thân',
                            'name' => 'Đắc Nhân Tâm',
                            'price' => '90.000 ₫',
                            'image' => 'https://via.placeholder.com/150',
                            'time' => '14/05/2025 - 15:30'
                        ],
                        [
                            'id' => 3,
                            'category' => 'Lịch sử',
                            'name' => 'Việt Nam Sử Lược',
                            'price' => '150.000 ₫',
                            'image' => 'https://via.placeholder.com/150',
                            'time' => '13/05/2025 - 10:00'
                        ],
                        [
                            'id' => 4,
                            'category' => 'Tiểu thuyết',
                            'name' => 'Người Đàn Ông Mang Tên Ove',
                            'price' => '130.000 ₫',
                            'image' => 'https://via.placeholder.com/150',
                            'time' => '12/05/2025 - 11:20'
                        ],
                        [
                            'id' => 5,
                            'category' => 'Phát triển bản thân',
                            'name' => 'Tư Duy Nhanh Và Chậm',
                            'price' => '180.000 ₫',
                            'image' => 'https://via.placeholder.com/150',
                            'time' => '11/05/2025 - 17:45'
                        ],
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
                            <div class="space-y-4 max-h-[calc(100vh-300px)] overflow-y-auto scrollbar-thin scrollbar-thumb-gray-300 scrollbar-track-gray-100">
                                @foreach ($products as $product)
                                    <div class="bg-white border border-gray-200 rounded-lg p-4 shadow-sm">
                                        <div class="flex items-center mb-3">
                                            <h2 class="text-sm font-semibold text-gray-800 uppercase">
                                                {{ $product['category'] }}
                                            </h2>
                                        </div>

                                        <hr class="border-gray-200 mb-3">

                                        <div class="flex items-start space-x-4">
                                            <div class="flex-shrink-0">
                                                <img class="h-16 w-16 object-contain"
                                                    src="{{ $product['image'] }}"
                                                   >
                                            </div>
                                            <div class="flex-1">
                                                <p class="text-sm font-bold text-gray-800 mb-1">
                                                    {{ $product['name'] }}
                                                </p>
                                                <p class="text-sm font-medium text-gray-800 mb-1">
                                                    Giá: {{ $product['price'] }}
                                                </p>
                                                <p class="text-xs text-gray-500">
                                                    Thêm vào yêu thích: {{ $product['time'] }}
                                                </p>
                                            </div>
                                            <form action="{{ route('wishlist.remove') }}" method="POST" class="flex items-center">
                                                @csrf
                                                <input type="hidden" name="product_id" value="{{ $product['id'] }}">
                                               <button type="submit" class="text-red-500 hover:text-red-600 focus:outline-none" title="Xóa khỏi yêu thích">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5-4h4M9 7v12m6-12v12M3 7h18"/>
                                                    </svg>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <style>
        .scrollbar-thin {
            scrollbar-width: thin;
        }
        .scrollbar-thumb-gray-300 {
            scrollbar-color: #d1d5db #f3f4f6;
        }
        .scrollbar-track-gray-100 {
            background: #f3f4f6;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const tabButtons = document.querySelectorAll('[data-tabs-target]');
            const tabContents = document.querySelectorAll('#notificationTabContent > .tab-content');

            tabButtons.forEach(button => {
                button.addEventListener('click', () => {
                    const targetId = button.getAttribute('data-tabs-target').substring(1);

                   
                    tabContents.forEach(content => content.classList.add('hidden'));

                   
                    tabButtons.forEach(btn => {
                        btn.classList.remove('border-red-500', 'text-red-500');
                        btn.classList.add('border-transparent', 'hover:text-gray-600', 'hover:border-gray-300');
                    });

                   
                    const targetContent = document.getElementById(targetId);
                    if (targetContent) {
                        targetContent.classList.remove('hidden');
                    }

                    button.classList.add('border-red-500', 'text-red-500');
                    button.classList.remove('border-transparent', 'hover:text-gray-600', 'hover:border-gray-300');
                });
            });
        });
    </script>
</x-layouts.layout>