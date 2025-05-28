<div  class="container max-w-[1200px] mx-auto">
    <!-- Grid Slider Header -->
    <div class="">
        <div class="bg-pink-100 mt-1.5 p-4 rounded flex justify-between items-center">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <img src="https://cdn1.fahasa.com/media/wysiwyg/icon-menu/icon_dealhot_new.png"
                        class="mx-auto w-10 h-auto" alt="Hot Deal Icon">
                </div>
                <div class="ml-2 text-lg font-bold text-gray-800 uppercase">Combo Sách</div>
            </div>
        </div>
    </div>
    <div class=" bg-white p-4 flex justify-end items-center mb-4">
        <div class="relative">
            <div class="border border-gray-300 rounded px-3 py-1.5 bg-white cursor-pointer flex items-center" onclick="toggleDropdown()">
                 <span class="text-sm text-gray-700 mr-2">Sắp xếp theo:</span>
                <span class="text-sm text-gray-800">Trending</span>
                <span class="ml-1 text-xs">▼</span>
            </div>
            <div id="dropdownMenu" class="absolute hidden top-full right-0 w-48 bg-white border border-gray-300 rounded shadow-lg z-10">
               
                <span class="block px-3 py-1.5 text-sm hover:bg-gray-100 cursor-pointer">Bán Chạy Tuần</span>
                <span class="block px-3 py-1.5 text-sm hover:bg-gray-100 cursor-pointer">Bán Chạy Tháng</span>
                <span class="block px-3 py-1.5 text-sm hover:bg-gray-100 cursor-pointer">Bán Chạy Năm</span>
                <span class="block px-3 py-1.5 text-sm hover:bg-gray-100 cursor-pointer">Nổi Bật Tuần</span>
                <span class="block px-3 py-1.5 text-sm hover:bg-gray-100 cursor-pointer">Nổi Bật Tháng</span>
                <span class="block px-3 py-1.5 text-sm hover:bg-gray-100 cursor-pointer">Nổi Bật Năm</span>
                <span class="block px-3 py-1.5 text-sm hover:bg-gray-100 cursor-pointer">Chiết khấu</span>
                <span class="block px-3 py-1.5 text-sm hover:bg-gray-100 cursor-pointer">Giá Bán</span>
                <span class="block px-3 py-1.5 text-sm hover:bg-gray-100 cursor-pointer">Mới nhất</span>
            </div>
        </div>
    </div>
    <!-- Grid Slider Content -->
    <div>
        <div class="ontainer mx-auto px-4flex flex-wrap gap-4">
            @forelse ($combos as $combo)
                <div
                    class="bg-white w-full sm:w-[calc(50%-1rem)] md:w-[calc(20%-1rem)] mb-4 rounded-lg shadow-sm hover:shadow-md transition-shadow">
                    <div>
                        @if (!empty($combo->images) && is_array($combo->images))
                            <img src="{{ asset('storage/' . $combo->images[0]) }}" class="w-full h-64 object-contain"
                                alt="{{ $combo->combo_name }}">
                        @else
                            <img src="{{ asset('images/placeholder.jpg') }}" class="w-full h-64 object-contain"
                                alt="Placeholder">
                        @endif
                    </div>
                    <div class="p-4">
                        <h3 class="text-sm font-medium text-gray-800 line-clamp-2">{{ $combo->combo_name }}</h3>
                        <div class="flex items-center mt-1">
                            <p class="text-red-500 font-bold text-sm">{{ number_format($combo->sale_price, 0, ',', '.') }}đ
                            </p>
                            @if ($combo->original_price > $combo->sale_price)
                                <span class="ml-2 bg-red-500 text-white text-xs font-bold px-2 py-1 rounded">
                                    Giảm
                                    {{ round(($combo->original_price - $combo->sale_price) / $combo->original_price * 100) }}%
                                </span>
                            @endif
                        </div>
                        <p class="text-gray-500 line-through text-xs">
                            {{ number_format($combo->original_price, 0, ',', '.') }}đ</p>
                    </div>
                </div>
            @empty
                <p class="text-gray-600">Không tìm thấy combo sách nào.</p>
            @endforelse
        </div>

       
        <div class="mt-6">
            {{ $combos->links() }}
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
     <livewire:suggest-products />
</div>
