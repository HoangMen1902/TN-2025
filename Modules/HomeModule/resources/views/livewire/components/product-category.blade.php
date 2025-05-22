<div class="">
    <div class="flex justify-between h-[40px]">
        <div class="w-1/2">
            <div class="flex items-center">
                <img src="https://cdn1.fahasa.com/skin/frontend/ma_vanese/fahasa/images/ico_menu_red.svg" 
                     class="h-5 w-5 mr-2" alt="Logo danh mục">
                <p class="text-xl font-bold">Danh mục sản phẩm</p>
            </div>
        </div>
    </div>

    <div class="fhs_block_line">
        <div class="border-t border-gray-300 my-2 mx-3"></div>
    </div>

    <div class="grid grid-cols-2 sm:grid-cols-5 lg:grid-cols-10 gap-2 h-[160px]">
        @foreach ($childCategories as $category)
            <a href="/san-pham"
               class="group p-2 flex flex-col items-center justify-between h-[150px] rounded transition-colors duration-200 ">
                <div class="h-[120px] flex items-center justify-center">
                    <img src="{{ $category->products->isNotEmpty() && $category->products->first()->thumbnail ? asset('storage/' . $category->products->first()->thumbnail) : 'https://via.placeholder.com/150' }}"
                         class="rounded h-full w-auto object-contain transition-transform duration-200 group-hover:scale-105">
                </div>
                <div class="text-center mt-1">
                    <p class="text-sm font-medium text-gray-800 transition-colors duration-200 group-hover:text-blue-500">
                        {{ $category->name }}
                    </p>
                </div>
            </a>
        @endforeach
    </div>
</div>
