<div class="w-[300px] min-h-screen p-4">
    <div class="flex items-center space-x-3 mb-6 pb-4 ">
        <div class="w-12 h-12 bg-gray-200 rounded-full flex items-center justify-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
            </svg>
        </div>
        <div>
            <div class="font-medium">Tên người dùng</div>
            <div class="text-gray-500 text-sm flex items-center">
                <a href="/ho-so" class="flex">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                    </svg>
                    Sửa Hồ Sơ
                </a>
            </div>
        </div>
    </div>

    <div class="space-y-1">
        <div class="flex items-center py-2 px-1 text-gray-700 hover:text-blue-600">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
            </svg>
            Thông Báo
        </div>

        <div>
            <div class="flex items-center justify-between py-2 px-1 cursor-pointer hover:text-blue-600" onclick="toggleAccountDropdown()">
                <div class="flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    <span>Tài Khoản Của Tôi</span>
                </div>
                <svg id="accountDropdownIcon" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 transform transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </div>

            <div id="accountDropdownMenu" class="pl-8 hidden">
                <div class="py-2 text-gray-700 cursor-pointer hover:text-blue-600">
                    <a href="/ho-so">Hồ Sơ</a>
                </div>

                <div class="py-2 text-gray-700 cursor-pointer hover:text-blue-600">
                    <a href="/dia-chi"></a>
                </div>

                <div class="py-2 text-gray-700 cursor-pointer hover:text-blue-600">
                    <a href="/doi-mat-khau">Đổi Mật Khẩu</a>
                </div>

                <div class="py-2 text-gray-700 cursor-pointer hover:text-blue-600">
                    Cài Đặt Thông Báo
                </div>

                <div class="py-2 text-gray-700 cursor-pointer hover:text-blue-600">
                    Những Thiết Lập Riêng Tư
                </div>
            </div>
        </div>

        <div class="flex items-center py-2 px-1 text-gray-700 hover:text-blue-600">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
            </svg>
            Đơn Mua
        </div>

        <div class="flex items-center py-2 px-1 text-gray-700 hover:text-blue-600">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" />
            </svg>
            Kho Voucher
        </div>
    </div>
</div>

<script>
    function toggleAccountDropdown() {
        const dropdownMenu = document.getElementById('accountDropdownMenu');
        const dropdownIcon = document.getElementById('accountDropdownIcon');

        dropdownMenu.classList.toggle('hidden');
        dropdownIcon.classList.toggle('rotate-180');
    }
</script>