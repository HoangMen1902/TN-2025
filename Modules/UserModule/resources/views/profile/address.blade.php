<x-layouts.layout>
    <div class="container mx-auto max-w-[1200px] my-3 md:my-6 flex flex-col px-4 md:px-0">
        <div class="flex flex-col md:flex-row md:h-[600px] w-full">
            <div class="w-full h-[500px] md:w-[300px] md:h-[500px] mb-4 md:mb-0">
                <x-usermodule::sidebar></x-usermodule::sidebar>
            </div>

            <div class="bg-white w-full md:w-[900px] py-4 md:py-8 rounded shadow-sm">
                <div class="mb-4 md:mb-6 flex justify-between items-center px-4">
                    <h1 class="text-lg md:text-2xl font-medium">Địa chỉ của tôi</h1>
                    <button data-modal-target="crud-modal" data-modal-toggle="crud-modal"
                        class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 md:px-4 md:py-2 rounded flex items-center text-sm md:text-base">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 md:h-5 md:w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        <span class="hidden xs:inline">Thêm địa chỉ mới</span>
                        <span class="xs:hidden">Thêm</span>
                    </button>
                </div>
                <hr class="border-t border-gray-300 my-2 md:my-4 mx-4">
                <div class="mb-6">
                    <h2 class="text-base p-4">Địa chỉ</h2>

                    <div class="bg-white rounded shadow-sm mb-4 p-3 md:p-4 mx-3 md:mx-4">
                        <div class="flex flex-col md:flex-row md:justify-between">
                            <div>
                                <div class="flex flex-col md:flex-row md:items-center md:gap-2 mb-1">
                                    <span class="font-medium">Nguyễn Hoài Bảo</span>
                                    <span class="hidden md:inline text-gray-500">|</span>
                                    <span class="text-gray-500">(+84) 845 456 683</span>
                                </div>
                                <p class="text-gray-600 text-sm mb-1">
                                    Hẻm 2 Đường Nguyễn Minh Quang, Khu Dân Cư An Khánh
                                </p>
                                <p class="text-gray-600 text-sm">
                                    Phường An Khánh, Quận Ninh Kiều, Cần Thơ
                                </p>
                                <div class="mt-2">
                                    <span class="text-xs border border-red-500 text-red-500 px-2 py-0.5 rounded">Mặc định</span>
                                </div>
                            </div>
                            <div class="flex flex-col md:flex-col mt-3 md:mt-0">
                                <div class="flex gap-4 mb-2">
                                    <button class="text-blue-500 cursor-pointer" data-modal-toggle="update-modal">Cập nhật</button>
                                    <label for="delete-modal" class="cursor-pointer text-red-500 hover:underline">Xóa</label>

                                </div>
                                <div>
                                    <button class="border border-gray-300 px-2 py-1 rounded text-xs">Thiết lập mặc định</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white rounded shadow-sm mb-4 p-3 md:p-4 mx-3 md:mx-4">
                        <div class="flex flex-col md:flex-row md:justify-between">
                            <div>
                                <div class="flex flex-col md:flex-row md:items-center md:gap-2 mb-1">
                                    <span class="font-medium">Nguyễn Hoài Bảo</span>
                                    <span class="hidden md:inline text-gray-500">|</span>
                                    <span class="text-gray-500">(+84) 845 456 683</span>
                                </div>
                                <p class="text-gray-600 text-sm mb-1">
                                    Ấp Tà Ốc
                                </p>
                                <p class="text-gray-600 text-sm">
                                    Xã Ninh Hòa, Huyện Hồng Dân, Bạc Liêu
                                </p>
                            </div>
                            <div class="flex flex-col md:flex-col mt-3 md:mt-0">
                                <div class="flex gap-4 mb-2">
                                    <button class="text-blue-500 cursor-pointer">Cập nhật</button>
                                    <label for="delete-modal" class="cursor-pointer text-red-500 hover:underline">Xóa</label>

                                </div>
                                <div>
                                    <button class="border border-gray-300 px-2 py-1 rounded text-xs">Thiết lập mặc định</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div id="crud-modal" tabindex="-1" aria-hidden="true" class="fixed inset-0 bg-opacity-50 top-0 md:top-10 flex items-center justify-center z-50 hidden">
            <div class="bg-white rounded-lg shadow-lg w-full max-w-md mx-4 transform transition-transform duration-300">
                <div class="p-4 md:p-6">
                    <div class="flex items-center justify-between mb-4 md:mb-6">
                        <h2 class="text-lg md:text-xl font-medium text-center w-full">Thêm địa chỉ mới</h2>
                        <button type="button" class="text-gray-500 hover:text-gray-700" data-modal-toggle="crud-modal">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                    <form>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3 md:gap-4 mb-4">
                            <input type="text" placeholder="Họ và tên" class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <input type="tel" placeholder="Số điện thoại" class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div class="mb-4">
                            <div class="relative">
                                <select class="appearance-none w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <option value="" selected disabled>Tỉnh/Thành phố</option>
                                    <option>Hà Nội</option>
                                    <option>Cần Thơ</option>
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                                    <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                        fill="currentColor">
                                        <path fill-rule="evenodd"
                                            d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </div>
                        </div>
                        <div class="mb-4">
                            <div class="relative">
                                <select class="appearance-none w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <option value="" selected disabled>Quận/Huyện</option>
                                    <option>Đà Nẵng</option>
                                    <option>Cần Thơ</option>
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                                    <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                        fill="currentColor">
                                        <path fill-rule="evenodd"
                                            d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </div>
                        </div>
                        <div class="mb-4">
                            <div class="relative">
                                <select class="appearance-none w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <option value="" selected disabled>Phường/Xã</option>
                                    <option>Đà Nẵng</option>
                                    <option>Cần Thơ</option>
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                                    <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                        fill="currentColor">
                                        <path fill-rule="evenodd"
                                            d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </div>
                        </div>
                        <div class="mb-4 md:mb-6">
                            <textarea placeholder="Địa chỉ cụ thể" class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500 h-20 md:h-24"></textarea>
                        </div>
                        <div class="mb-4 md:mb-6">
                            <button type="button" class="flex items-center justify-center w-full border border-gray-300 rounded py-2 text-gray-600 hover:bg-gray-50">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 md:h-5 md:w-5 mr-2" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                Thêm vị trí
                            </button>
                        </div>

                        <div class="mb-4 md:mb-6">
                            <p class="text-sm mb-2">Loại địa chỉ:</p>
                            <div class="flex gap-3 md:gap-4">
                                <button type="button" class="px-3 md:px-4 py-2 border border-gray-300 rounded hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm md:text-base">Nhà Riêng</button>
                                <button type="button" class="px-3 md:px-4 py-2 border border-gray-300 rounded hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm md:text-base">Văn Phòng</button>
                            </div>
                        </div>

                        <div class="flex items-center mb-4 md:mb-6">
                            <input type="checkbox" id="defaultAddress" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                            <label for="defaultAddress" class="ml-2 text-gray-700 text-sm md:text-base">Đặt làm địa chỉ mặc định</label>
                        </div>

                        <div class="flex gap-3 md:gap-4">
                            <button type="button" class="flex-1 py-2 border border-red-500 rounded font-medium text-red-500 text-sm md:text-base" data-modal-toggle="crud-modal">Trở Lại</button>
                            <button type="submit" class="flex-1 py-2 bg-red-500 hover:bg-red-600 rounded font-medium text-white text-sm md:text-base">Hoàn thành</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- Modal Form Cập nhật -->
        <div id="update-modal" tabindex="-1" aria-hidden="true" class="fixed inset-0 bg-opacity-50 top-0 md:top-10 flex items-center justify-center z-50 hidden">
            <div class="bg-white rounded-lg shadow-lg w-full max-w-md mx-4 transform transition-transform duration-300">
                <div class="p-4 md:p-6">
                    <div class="flex items-center justify-between mb-4 md:mb-6">
                        <h2 class="text-lg md:text-xl font-medium text-center w-full">Cập nhật địa chỉ</h2>
                        <button type="button" class="text-gray-500 hover:text-gray-700" data-modal-toggle="update-modal">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                    <form>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3 md:gap-4 mb-4">
                            <input type="text" placeholder="Họ và tên" class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <input type="tel" placeholder="Số điện thoại" class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div class="mb-4">
                            <div class="relative">
                                <select class="appearance-none w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <option value="" selected disabled>Tỉnh/Thành phố</option>
                                    <option>Hà Nội</option>
                                    <option>Cần Thơ</option>
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                                    <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                        fill="currentColor">
                                        <path fill-rule="evenodd"
                                            d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </div>
                        </div>
                        <div class="mb-4">
                            <div class="relative">
                                <select class="appearance-none w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <option value="" selected disabled>Quận/Huyện</option>
                                    <option>Đà Nẵng</option>
                                    <option>Cần Thơ</option>
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                                    <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                        fill="currentColor">
                                        <path fill-rule="evenodd"
                                            d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </div>
                        </div>
                        <div class="mb-4">
                            <div class="relative">
                                <select class="appearance-none w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <option value="" selected disabled>Phường/Xã</option>
                                    <option>Đà Nẵng</option>
                                    <option>Cần Thơ</option>
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                                    <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                        fill="currentColor">
                                        <path fill-rule="evenodd"
                                            d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </div>
                        </div>
                        <div class="mb-4 md:mb-6">
                            <textarea placeholder="Địa chỉ cụ thể" class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500 h-20 md:h-24"></textarea>
                        </div>
                        <div class="mb-4 md:mb-6">
                            <button type="button" class="flex items-center justify-center w-full border border-gray-300 rounded py-2 text-gray-600 hover:bg-gray-50">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 md:h-5 md:w-5 mr-2" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                Thêm vị trí
                            </button>
                        </div>

                        <div class="mb-4 md:mb-6">
                            <p class="text-sm mb-2">Loại địa chỉ:</p>
                            <div class="flex gap-3 md:gap-4">
                                <button type="button" class="px-3 md:px-4 py-2 border border-gray-300 rounded hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm md:text-base">Nhà Riêng</button>
                                <button type="button" class="px-3 md:px-4 py-2 border border-gray-300 rounded hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm md:text-base">Văn Phòng</button>
                            </div>
                        </div>

                        <div class="flex items-center mb-4 md:mb-6">
                            <input type="checkbox" id="defaultAddress" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                            <label for="defaultAddress" class="ml-2 text-gray-700 text-sm md:text-base">Đặt làm địa chỉ mặc định</label>
                        </div>

                        <div class="flex gap-3 md:gap-4">
                            <button type="button" class="flex-1 py-2 border border-red-500 rounded font-medium text-red-500 text-sm md:text-base" data-modal-toggle="update-modal">Trở Lại</button>
                            <button type="submit" class="flex-1 py-2 bg-red-500 hover:bg-red-600 rounded font-medium text-white text-sm md:text-base">Hoàn thành</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <input type="checkbox" id="delete-modal" class="modal-toggle hidden peer" />

        <!-- Overlay -->
        <div class="fixed inset-0  bg-opacity-50 z-40 hidden peer-checked:block">
            <div class="fixed inset-0 flex items-center justify-center z-50 px-4">
                <div class="bg-white p-4 rounded-lg shadow-lg w-full max-w-xs sm:max-w-sm">
                    <h2 class="text-base font-semibold text-center mb-3">
                        Xác nhận xóa địa chỉ
                    </h2>
                    <p class="text-sm text-gray-600 text-center mb-5">
                        Bạn có chắc chắn muốn xóa địa chỉ này không?
                    </p>
                    <div class="flex justify-end gap-2">
                        <label for="delete-modal"
                            class="px-3 py-1.5 border border-gray-300 text-gray-700 rounded hover:bg-gray-100 cursor-pointer text-sm">
                            Hủy
                        </label>
                        <button class="px-3 py-1.5 bg-red-500 text-white rounded hover:bg-red-600 text-sm">
                            Xóa
                        </button>
                    </div>
                </div>
            </div>
        </div>

    </div>
</x-layouts.layout>