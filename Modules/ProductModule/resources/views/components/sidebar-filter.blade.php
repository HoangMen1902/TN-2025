<div class="wrapper">
<div class="" >
    <div class="w-72 p-4  rounded-l space-y-4 border border-gray-300 ">
        <h2 class="text-lg font-semibold"></h2>

        <!-- Price Range -->
        <div>
            <label class="block border-gray-300  mb-1 font-bold">Khoản giá</label>
            <input type="range" class="w-full accent-blue-500" min="0" max="5000" />
            <div class="flex justify-between mt-2 gap-2">
                <input type="number" class="w-1/2 p-1 rounded  border border-gray-300 " value="300">
                <input type="number" class="w-1/2 p-1 rounded  border border-gray-300 " value="3500">
            </div>
        </div>

        <!-- Sales -->
        <div>
            <label class="block border-gray-300  mb-1 font-bold">Giảm giá</label>
            <input type="range" class="w-full accent-blue-500" min="0" max="100" />
            <div class="flex justify-between mt-2 gap-2">
                <input type="number" class="w-1/2 p-1 rounded  border border-gray-300 " value="1">
                <input type="number" class="w-1/2 p-1 rounded  border border-gray-300 " value="100">
            </div>
        </div>

        <!-- Category -->
        <div>
            <label class="block mb-1 font-bold">Loại sản phẩm</label>
            <div id="category-buttons" class="grid grid-cols-2 gap-2">
                <button type="button" data-value="Gaming"
                    class="category-btn px-2 py-1 rounded  border border-gray-300 hover:bg-blue-500">Văn Học</button>
                <button type="button" data-value="Electronics"
                    class="category-btn px-2 py-1 rounded  border border-gray-300 hover:bg-blue-500">Truyện</button>
                <button type="button" data-value="Phone"
                    class="category-btn px-2 py-1 rounded  border border-gray-300 hover:bg-blue-500">Tiếng Anh</button>
                <button type="button" data-value="TV/Monitor"
                    class="category-btn px-2 py-1 rounded border border-gray-300  hover:bg-blue-500">Thiếu Nhi</button>
                <button type="button" data-value="Laptop"
                    class="category-btn px-2 py-1 rounded border border-gray-300  hover:bg-blue-500">Trinh Thám</button>
                <button type="button" data-value="Watch" class="category-btn px-2 py-1 rounded border border-gray-300  h
                   over:bg-blue-500">Sách Giải</button>
            </div>
        </div>

        <!-- Hidden input to send to backend -->
        <input type="hidden" id="selected-categories" name="categories" value="">

        <!-- State -->
        {{-- <div>
            <label class="block border-gray-300  mb-1">State</label>
            <div class="space-y-2">
                <label class="flex items-center gap-2">
                    <input type="radio" name="state" checked class="accent-blue-500">
                    <span>All</span>
                </label>
                <label class="flex items-center gap-2">
                    <input type="radio" name="state" class="accent-blue-500">
                    <span>New</span>
                </label>
                <label class="flex items-center gap-2">
                    <input type="radio" name="state" class="accent-blue-500">
                    <span>Refurbished</span>
                </label>
            </div>
        </div>
        --}}
        <!-- Buttons -->
        <div class="flex gap-2">
            {{-- <button class="flex-1 bg-blue-600 hover:bg-blue-700  px-4 py-2 rounded">Thêm kết quả khác</button> --}}
            <button class="bg-blue-600 text-white hover:bg-blue-700  px-4 py-2 rounded">Đặt lại</button>
        </div>
    </div>
</div>
</div>
<script>
    const selected = new Set();
    const buttons = document.querySelectorAll('.category-btn');
    const hiddenInput = document.getElementById('selected-categories');

    buttons.forEach(button => {
        button.addEventListener('click', () => {
            const value = button.dataset.value;
            if (selected.has(value)) {
                selected.delete(value);
                button.classList.remove('bg-blue-600');
                button.classList.add('bg-gray-700');
            } else {
                selected.add(value);
                button.classList.remove('bg-gray-700');
                button.classList.add('bg-blue-600');
            }

            hiddenInput.value = Array.from(selected).join(',');
        });
    });
</script>