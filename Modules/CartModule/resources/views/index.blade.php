<x-layouts.layout>
    <x-slot name="title">BeeBook - Giỏ hàng </x-slot>
    <div class="cart-container mx-auto my-5 w-4/5 bg-white p-5 shadow-md flex justify-between items-stretch">
        <div class="cart flex-1 flex flex-col bg-white p-5 border border-gray-300 mb-5 lg:mb-0 lg:mr-5">
            <form action="/delete-all-cart" method="post">
                <input type="hidden" name="method" value="POST">
                <button class="text-lg font-medium  hover:underline" name="delete-cart" onclick="return confirm('Xóa toàn bộ sản phẩm')">Xóa toàn bộ sản phẩm</button>
            </form>
            <h3 class="text-center mt-7 text-2xl">Giỏ hàng</h3>
            <p class="text-center mt-3 text-lg  border-b-4 border-blue-600 w-72 mx-auto">Bạn được giao hàng miễn phí!</p>
    
            <div class="cart__content flex flex-col lg:flex-row justify-start px-5 mt-5">
                <div class="cart__products w-5/7">
                    <div class="flex justify-between mb-7 border-b border-gray-400">
                        <div class="w-1/7 font-bold">Hình ảnh</div>
                        <div class="w-4/7 font-bold">Sản phẩm</div>
                        <div class="w-1/7 font-bold">Số lượng</div>
                        <div class="w-1/7 font-bold">Tổng</div>
                    </div>

                    <div class="w-full mb-5">
                        <div class="border-b p-2 flex border-gray-400">
                            <div class="w-1/7 p-2">
                                <img class="w-24 h-auto" src="https://cdn1.fahasa.com/media/catalog/product/8/9/8935244885538.jpg" alt="SKU123">
                            </div>
                            <div class="w-4/7 p-2">
                                <h2 class="text-lg font-bold truncate max-w-100 mt-1">Laptop ABC - SKU123Laptop ABC - SKU123Laptop ABC - SKU123Laptop ABC - SKU123Laptop ABC - SKU123Laptop ABC - SKU123Laptop ABC - SKU123</h2>
                                <p class="">15,000,000đ</p>
                                <div class="list-none p-0 text-sm ">
                                    <div class="my-1 line-clamp-2">Mô tả sản phẩm: Laptop ABC chất lượng cao. Mô tả sản phẩm: Laptop ABC chất lượng cao. Mô tả sản phẩm: Laptop ABC chất lượng cao. Mô tả sản phẩm: Laptop ABC chất lượng cao.</div>
                                </div>
                            </div>
                            <div class="w-1/7 p-2">
                                <form action="/update-cart" method="post" class="flex items-center justify-center">
                                    <input class="w-10 h-10 rounded-full border border-gray-500 text-center" type="text" name="quantity[1]" value="1" data-id="1">
                                </form>
                                <form action="/delete-cart-item" method="post" class="flex items-center justify-center">
                                    <input type="hidden" name="id" value="1">
                                    <input type="hidden" name="method" value="POST">
                                    <button name="delete-cart-item" class="text-gray-600 hover:text-red-500">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor" class="w-6 h-6">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m9.75 9.75 4.5 4.5m0-4.5-4.5 4.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                        </svg>
                                    </button>
                                </form>
                            </div>
                            <div class="w-1/7 text-center mt-4">15,000,000 đ</div>
                        </div>
                    </div>
                    <div class="w-full mb-5">
                        <div class="border-b p-2 flex border-gray-400">
                            <div class="w-1/7 p-2">
                                <img class="w-24 h-auto" src="https://cdn1.fahasa.com/media/catalog/product/8/9/8935244885538.jpg" alt="SKU123">
                            </div>
                            <div class="w-4/7 p-2">
                                <h2 class="text-lg font-bold truncate max-w-100 mt-1">Laptop ABC - SKU123Laptop ABC - SKU123Laptop ABC - SKU123Laptop ABC - SKU123Laptop ABC - SKU123Laptop ABC - SKU123Laptop ABC - SKU123</h2>
                                <p class="">15,000,000đ</p>
                                <div class="list-none p-0 text-sm ">
                                    <div class="my-1 line-clamp-2">Mô tả sản phẩm: Laptop ABC chất lượng cao. Mô tả sản phẩm: Laptop ABC chất lượng cao. Mô tả sản phẩm: Laptop ABC chất lượng cao. Mô tả sản phẩm: Laptop ABC chất lượng cao.</div>
                                </div>
                            </div>
                            <div class="w-1/7 p-2">
                                <form action="/update-cart" method="post" class="flex items-center justify-center">
                                    <input class="w-10 h-10 rounded-full border border-gray-500 text-center" type="text" name="quantity[1]" value="1" data-id="1">
                                </form>
                                <form action="/delete-cart-item" method="post" class="flex items-center justify-center">
                                    <input type="hidden" name="id" value="1">
                                    <input type="hidden" name="method" value="POST">
                                    <button name="delete-cart-item" class="text-gray-600 hover:text-red-500">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor" class="w-6 h-6">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m9.75 9.75 4.5 4.5m0-4.5-4.5 4.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                        </svg>
                                    </button>
                                </form>
                            </div>
                            <div class="w-1/7 text-center mt-4">15,000,000 đ</div>
                        </div>
                    </div>   <div class="w-full mb-5">
                        <div class="border-b p-2 flex border-gray-400">
                            <div class="w-1/7 p-2">
                                <img class="w-24 h-auto" src="https://cdn1.fahasa.com/media/catalog/product/8/9/8935244885538.jpg" alt="SKU123">
                            </div>
                            <div class="w-4/7 p-2">
                                <h2 class="text-lg font-bold truncate max-w-100 mt-1">Laptop ABC - SKU123Laptop ABC - SKU123Laptop ABC - SKU123Laptop ABC - SKU123Laptop ABC - SKU123Laptop ABC - SKU123Laptop ABC - SKU123</h2>
                                <p class="">15,000,000đ</p>
                                <div class="list-none p-0 text-sm ">
                                    <div class="my-1 line-clamp-2">Mô tả sản phẩm: Laptop ABC chất lượng cao. Mô tả sản phẩm: Laptop ABC chất lượng cao. Mô tả sản phẩm: Laptop ABC chất lượng cao. Mô tả sản phẩm: Laptop ABC chất lượng cao.</div>
                                </div>
                            </div>
                            <div class="w-1/7 p-2">
                                <form action="/update-cart" method="post" class="flex items-center justify-center">
                                    <input class="w-10 h-10 rounded-full border border-gray-500 text-center" type="text" name="quantity[1]" value="1" data-id="1">
                                </form>
                                <form action="/delete-cart-item" method="post" class="flex items-center justify-center">
                                    <input type="hidden" name="id" value="1">
                                    <input type="hidden" name="method" value="POST">
                                    <button name="delete-cart-item" class="text-gray-600 hover:text-red-500">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor" class="w-6 h-6">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m9.75 9.75 4.5 4.5m0-4.5-4.5 4.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                        </svg>
                                    </button>
                                </form>
                            </div>
                            <div class="w-1/7 text-center mt-4">15,000,000 đ</div>
                        </div>
                    </div>   <div class="w-full mb-5">
                        <div class="border-b p-2 flex border-gray-400">
                            <div class="w-1/7 p-2">
                                <img class="w-24 h-auto" src="https://cdn1.fahasa.com/media/catalog/product/8/9/8935244885538.jpg" alt="SKU123">
                            </div>
                            <div class="w-4/7 p-2">
                                <h2 class="text-lg font-bold truncate max-w-100 mt-1">Laptop ABC - SKU123Laptop ABC - SKU123Laptop ABC - SKU123Laptop ABC - SKU123Laptop ABC - SKU123Laptop ABC - SKU123Laptop ABC - SKU123</h2>
                                <p class="">15,000,000đ</p>
                                <div class="list-none p-0 text-sm ">
                                    <div class="my-1 line-clamp-2">Mô tả sản phẩm: Laptop ABC chất lượng cao. Mô tả sản phẩm: Laptop ABC chất lượng cao. Mô tả sản phẩm: Laptop ABC chất lượng cao. Mô tả sản phẩm: Laptop ABC chất lượng cao.</div>
                                </div>
                            </div>
                            <div class="w-1/7 p-2">
                                <form action="/update-cart" method="post" class="flex items-center justify-center">
                                    <input class="w-10 h-10 rounded-full border border-gray-500 text-center" type="text" name="quantity[1]" value="1" data-id="1">
                                </form>
                                <form action="/delete-cart-item" method="post" class="flex items-center justify-center">
                                    <input type="hidden" name="id" value="1">
                                    <input type="hidden" name="method" value="POST">
                                    <button name="delete-cart-item" class="text-gray-600 hover:text-red-500">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor" class="w-6 h-6">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m9.75 9.75 4.5 4.5m0-4.5-4.5 4.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                        </svg>
                                    </button>
                                </form>
                            </div>
                            <div class="w-1/7 text-center mt-4">15,000,000 đ</div>
                        </div>
                    </div>   <div class="w-full mb-5">
                        <div class="border-b p-2 flex border-gray-400">
                            <div class="w-1/7 p-2">
                                <img class="w-24 h-auto" src="https://cdn1.fahasa.com/media/catalog/product/8/9/8935244885538.jpg" alt="SKU123">
                            </div>
                            <div class="w-4/7 p-2">
                                <h2 class="text-lg font-bold truncate max-w-100 mt-1">Laptop ABC - SKU123Laptop ABC - SKU123Laptop ABC - SKU123Laptop ABC - SKU123Laptop ABC - SKU123Laptop ABC - SKU123Laptop ABC - SKU123</h2>
                                <p class="">15,000,000đ</p>
                                <div class="list-none p-0 text-sm ">
                                    <div class="my-1 line-clamp-2">Mô tả sản phẩm: Laptop ABC chất lượng cao. Mô tả sản phẩm: Laptop ABC chất lượng cao. Mô tả sản phẩm: Laptop ABC chất lượng cao. Mô tả sản phẩm: Laptop ABC chất lượng cao.</div>
                                </div>
                            </div>
                            <div class="w-1/7 p-2">
                                <form action="/update-cart" method="post" class="flex items-center justify-center">
                                    <input class="w-10 h-10 rounded-full border border-gray-500 text-center" type="text" name="quantity[1]" value="1" data-id="1">
                                </form>
                                <form action="/delete-cart-item" method="post" class="flex items-center justify-center">
                                    <input type="hidden" name="id" value="1">
                                    <input type="hidden" name="method" value="POST">
                                    <button name="delete-cart-item" class="text-gray-600 hover:text-red-500">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor" class="w-6 h-6">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m9.75 9.75 4.5 4.5m0-4.5-4.5 4.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                        </svg>
                                    </button>
                                </form>
                            </div>
                            <div class="w-1/7 text-center mt-4">15,000,000 đ</div>
                        </div>
                    </div>   <div class="w-full mb-5">
                        <div class="border-b p-2 flex border-gray-400">
                            <div class="w-1/7 p-2">
                                <img class="w-24 h-auto" src="https://cdn1.fahasa.com/media/catalog/product/8/9/8935244885538.jpg" alt="SKU123">
                            </div>
                            <div class="w-4/7 p-2">
                                <h2 class="text-lg font-bold truncate max-w-100 mt-1">Laptop ABC - SKU123Laptop ABC - SKU123Laptop ABC - SKU123Laptop ABC - SKU123Laptop ABC - SKU123Laptop ABC - SKU123Laptop ABC - SKU123</h2>
                                <p class="">15,000,000đ</p>
                                <div class="list-none p-0 text-sm ">
                                    <div class="my-1 line-clamp-2">Mô tả sản phẩm: Laptop ABC chất lượng cao. Mô tả sản phẩm: Laptop ABC chất lượng cao. Mô tả sản phẩm: Laptop ABC chất lượng cao. Mô tả sản phẩm: Laptop ABC chất lượng cao.</div>
                                </div>
                            </div>
                            <div class="w-1/7 p-2">
                                <form action="/update-cart" method="post" class="flex items-center justify-center">
                                    <input class="w-10 h-10 rounded-full border border-gray-500 text-center" type="text" name="quantity[1]" value="1" data-id="1">
                                </form>
                                <form action="/delete-cart-item" method="post" class="flex items-center justify-center">
                                    <input type="hidden" name="id" value="1">
                                    <input type="hidden" name="method" value="POST">
                                    <button name="delete-cart-item" class="text-gray-600 hover:text-red-500">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor" class="w-6 h-6">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m9.75 9.75 4.5 4.5m0-4.5-4.5 4.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                        </svg>
                                    </button>
                                </form>
                            </div>
                            <div class="w-1/7 text-center mt-4">15,000,000 đ</div>
                        </div>
                    </div>
                
                </div>
                <div class="cart__summary w-2/7 ml-8 p-5 max-h-[230px] border border-gray-300 rounded-xl text-justify">
                    <div class="cart__summary-item flex justify-between mb-3">
                        <span class="font-medium">Tổng phụ</span>
                        <span class="text-gray-700">15,000,000 đ</span>
                    </div> 
                    <div class="cart__summary-item flex justify-between mb-3">
                        <h3 class="font-bold text-xl">15,000,000 đ</h3>
                    </div>
                    <p class="text-sm text-gray-600">Phí ship sẽ được tính khi thanh toán</p>
                    <div class="mt-5 flex justify-center">
                        <a href="/checkout" class="bg-primary text-white font-bold py-2 px-4 rounded-xl transition duration-300 hover:bg-white hover:text-black">Thanh toán</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
</x-layouts.layout>