<div class="container max-w-[1200px] mx-auto bg-white rounded mb-4">
    <!-- Grid Slider Header -->
    <div class="">
        <div class="bg-pink-100 mt-1 p-2 rounded flex justify-between items-center">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <img src="https://cdn1.fahasa.com/media/wysiwyg/icon-menu/icon_dealhot_new.png"
                        class="mx-auto w-8 h-auto" alt="Hot Deal Icon">
                </div>
                <div class="ml-2 text-base font-bold text-gray-800 uppercase">Combo Sản Phẩm</div>
            </div>
        </div>
    </div>
    <div class="bg-white p-2 flex justify-end items-center mb-2">
        <div class="relative">
            <div class="border border-gray-300 rounded px-2 py-1 bg-white cursor-pointer flex items-center" onclick="toggleDropdown()">
                <span class="text-xs text-gray-700 mr-1">Sắp xếp theo:</span>
                <span class="text-xs text-gray-800">Trending</span>
                <span class="ml-1 text-xs">▼</span>
            </div>
            <div id="dropdownMenu" class="absolute hidden top-full right-0 w-40 bg-white border border-gray-300 rounded shadow-lg z-10">
                <span class="block px-2 py-1 text-xs hover:bg-gray-100 cursor-pointer">Bán Chạy Tuần</span>
                <span class="block px-2 py-1 text-xs hover:bg-gray-100 cursor-pointer">Bán Chạy Tháng</span>
                <span class="block px-2 py-1 text-xs hover:bg-gray-100 cursor-pointer">Bán Chạy Năm</span>
                <span class="block px-2 py-1 text-xs hover:bg-gray-100 cursor-pointer">Nổi Bật Tuần</span>
                <span class="block px-2 py-1 text-xs hover:bg-gray-100 cursor-pointer">Nổi Bật Tháng</span>
                <span class="block px-2 py-1 text-xs hover:bg-gray-100 cursor-pointer">Nổi Bật Năm</span>
                <span class="block px-2 py-1 text-xs hover:bg-gray-100 cursor-pointer">Chiết khấu</span>
                <span class="block px-2 py-1 text-xs hover:bg-gray-100 cursor-pointer">Giá Bán</span>
                <span class="block px-2 py-1 text-xs hover:bg-gray-100 cursor-pointer">Mới nhất</span>
            </div>
        </div>
    </div>
    <!-- Grid Slider Content -->
    <div>
        <div class="flex flex-wrap gap-3 p-3">
            @forelse ($combos as $combo)
                <a href="/chi-tiet-combo/{{ $combo->slug }}" class="block w-full sm:w-[calc(50%-0.75rem)] md:w-[calc(20%-0.75rem)] mb-3">
                    <div class="bg-white rounded-lg shadow-sm hover:shadow-md transition-shadow">
                        <div>
                            @if (!empty($combo->images) && is_array($combo->images))
                                <img src="{{ asset('storage/' . $combo->images[0]) }}" class="w-full h-40 object-contain"
                                    alt="{{ $combo->combo_name }}">
                            @else
                                <img src="{{ asset('images/placeholder.jpg') }}" class="w-full h-48 object-contain"
                                    alt="Placeholder">
                            @endif
                        </div>
                        <div class="p-3">
                            <h3 class="text-xs font-medium text-gray-800 line-clamp-2">{{ $combo->combo_name }}</h3>
                            <div class="flex items-center mt-0.5">
                                <p class="text-red-500 font-bold text-xs">{{ number_format($combo->sale_price, 0, ',', '.') }}đ
                                </p>
                                @if ($combo->original_price > $combo->sale_price)
                                    <span class="ml-1 bg-red-500 text-white text-[10px] font-bold px-1 py-0.5 rounded">
                                        Giảm
                                        {{ round(($combo->original_price - $combo->sale_price) / $combo->original_price * 100) }}%
                                    </span>
                                @endif
                            </div>
                            <p class="text-gray-500 line-through text-[10px]">
                                {{ number_format($combo->original_price, 0, ',', '.') }}đ</p>
                            <div class="relative w-full h-3 bg-gray-300 rounded-full overflow-hidden mt-1">
                                <div class="absolute top-0 left-0 h-full bg-red-600 rounded-full" style="width: 20%;"></div>
                                <div class="absolute w-full text-center text-white text-[10px] leading-3">Đã bán 6</div>
                            </div>
                        </div>
                    </div>
                </a>
            @empty
                <p class="text-gray-600 text-sm">Không tìm thấy combo sách nào.</p>
            @endforelse
        </div>

        <div class="mt-4">
            {{ $combos->links() }}
        </div>
        <div class="text-center mt-3 p-3">
            <a href="/combo" class="inline-block px-8 py-1.5 border-2 border-red-600 rounded text-red-600 font-bold hover:bg-red-600 hover:text-white transition-colors duration-300">Xem Thêm</a>
        </div>
    </div>

    <style>
        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
    </style>
    <script>
        function toggleDropdown() {
            const dropdown = document.getElementById('dropdownMenu');
            dropdown.classList.toggle('hidden');
        }

        document.addEventListener('click', function(event) {
            const dropdown = document.getElementById('dropdownMenu');
            const button = dropdown.previousElementSibling;
            if (!button.contains(event.target) && !dropdown.contains(event.target)) {
                dropdown.classList.add('hidden');
            }
        });
    </script>
</div>