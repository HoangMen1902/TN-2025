    <div class="mx-auto py-4 md:py-8 px-4">
        <div class="md:hidden mb-6">
            <div class="w-full flex flex-col items-center">
                <div class="w-24 h-24 md:w-32 md:h-32 bg-gray-200 rounded-full flex items-center justify-center overflow-hidden">
                    <img id="avatarPreview-mobile" src="" alt="Avatar" class="hidden w-full h-full object-cover rounded-full" />
                    <svg id="avatarIcon-mobile" xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 md:h-16 md:w-16 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                </div>

                <input type="file" accept="image/jpeg,image/png" id="avatarInput-mobile" class="hidden" onchange="previewAvatarMobile(event)" />

                <button onclick="document.getElementById('avatarInput-mobile').click()" class="mt-3 px-3 py-1.5 text-sm border border-gray-300 rounded hover:bg-gray-100">
                    Chọn Ảnh
                </button>

                <p class="text-gray-500 text-xs mt-1 text-center">Dung lượng file tối đa 1 MB</p>
                <p class="text-gray-500 text-xs text-center">Định dạng: JPEG, PNG</p>
            </div>
        </div>

        <div class="flex flex-col md:flex md:flex-row md:gap-8">
            <!-- Form chỉnh sửa thông tin -->
            <div class="w-full md:w-2/3">
                <form wire:submit.prevent="save">
                    <!-- Username -->
                    <div class="mb-3 md:mb-4 flex flex-col md:flex md:flex-row md:items-center">
                        <label class="w-full md:w-1/3 text-gray-600 text-sm md:text-right pr-0 md:pr-4 mb-1 md:mb-0">Tên đăng nhập</label>
                        <div class="w-full md:w-2/3">
                            <input type="text" value="{{ $name }}" class="w-full border border-gray-300 rounded px-3 py-1.5 md:py-2 text-sm md:text-base" readonly>
                            <p class="text-gray-500 text-xs mt-1">Tên Đăng nhập chỉ có thể thay đổi một lần.</p>
                        </div>
                    </div>

                    <!-- Tên -->
                    <div class="mb-3 md:mb-4 flex flex-col md:flex md:flex-row md:items-center">
                        <label class="w-full md:w-1/3 text-gray-600 text-sm md:text-right pr-0 md:pr-4 mb-1 md:mb-0">Tên</label>
                        <div class="w-full md:w-2/3">
                            <input type="text" wire:model.defer="name" class="w-full border border-gray-300 rounded px-3 py-1.5 md:py-2 text-sm md:text-base">
                            @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <!-- Email -->
                    <div class="mb-3 md:mb-4 flex flex-col md:flex md:flex-row md:items-center">
                        <label class="w-full md:w-1/3 text-gray-600 text-sm md:text-right pr-0 md:pr-4 mb-1 md:mb-0">Email</label>
                        <div class="w-full md:w-2/3">
                            <div class="flex flex-row items-center">
                                <span class="border border-gray-300 rounded px-3 py-1.5 md:py-2 flex-grow text-sm md:text-base">{{ $email }}</span>
                                <a href="#" id="changeMailBtn" class="text-blue-500 ml-2 py-1.5 md:py-2 whitespace-nowrap text-sm md:text-base">Thay Đổi</a>
                            </div>
                        </div>
                    </div>

                    <!-- Số điện thoại -->
                    <div class="mb-3 md:mb-4 flex flex-col md:flex md:flex-row md:items-center">
                        <label class="w-full md:w-1/3 text-gray-600 text-sm md:text-right pr-0 md:pr-4 mb-1 md:mb-0">Số điện thoại</label>
                        <div class="w-full md:w-2/3">
                            <div class="flex flex-row items-center">
                                <span class="border border-gray-300 rounded px-3 py-1.5 md:py-2 flex-grow text-sm md:text-base">{{ $phone }}</span>
                                <a href="#" id="changePhoneBtn" class="text-blue-500 ml-2 py-1.5 md:py-2 whitespace-nowrap text-sm md:text-base">Thay Đổi</a>
                            </div>
                        </div>
                    </div>

                    <!-- Giới tính -->
                    <div class="mb-3 md:mb-4 flex flex-col md:flex md:flex-row md:items-center">
                        <label class="w-full md:w-1/3 text-gray-600 text-sm md:text-right pr-0 md:pr-4 mb-1 md:mb-0">Giới tính</label>
                        <div class="w-full md:w-2/3 flex flex-wrap gap-4">
                            <label class="flex items-center">
                                <input type="radio" wire:model.defer="gender" name="gender" value="Nam" class="mr-1">
                                <span class="text-sm md:text-base">Nam</span>
                            </label>
                            <label class="flex items-center">
                                <input type="radio" wire:model.defer="gender" name="gender" value="Nữ" class="mr-1">
                                <span class="text-sm md:text-base">Nữ</span>
                            </label>
                            <label class="flex items-center">
                                <input type="radio" wire:model.defer="gender" name="gender" value="Khác" class="mr-1">
                                <span class="text-sm md:text-base">Khác</span>
                            </label>
                        </div>
                        @error('gender') <span class="text-red-500 text-xs md:ml-1">{{ $message }}</span> @enderror
                    </div>

                    <!-- Ngày sinh -->

                    <div class="mb-3 md:mb-4 flex flex-col md:flex md:flex-row md:items-center">
                        <label class="w-full md:w-1/3 text-gray-600 text-sm md:text-right pr-0 md:pr-4 mb-1 md:mb-0">Ngày sinh</label>
                        <div class="w-full md:w-2/3">
                            <input
                                type="date"
                                wire:model.defer="birthday"
                                class="border border-gray-300 rounded px-3 py-1.5 md:py-2 w-full text-sm md:text-base"
                                max="{{ date('Y-m-d') }}" />
                            @error('birthday')
                            <p class="text-red-500 text-xs md:ml-1 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Nút lưu -->
                    <div class="mt-4 md:mt-6 flex flex-col md:flex md:flex-row">
                        <div class="w-full md:w-1/3 mb-0 md:mb-0"></div>
                        <div class="w-full md:w-2/3">
                            <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-medium px-4 md:px-6 py-1.5 md:py-2 rounded text-sm md:text-base">Lưu</button>
                        </div>
                    </div>
                </form>

            </div>

            <div class="hidden md:flex md:flex-col md:w-1/3 md:items-center">
                <div class="w-32 h-32 bg-gray-200 rounded-full flex items-center justify-center overflow-hidden">
                    <img id="avatarPreview" src="" alt="Avatar" class="hidden w-full h-full object-cover rounded-full" />
                    <svg id="avatarIcon" xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                </div>

                <input type="file" accept="image/jpeg,image/png" id="avatarInput" class="hidden" onchange="previewAvatar(event)" />

                <button onclick="document.getElementById('avatarInput').click()" class="mt-4 px-4 py-2 border border-gray-300 rounded hover:bg-gray-100">
                    Chọn Ảnh
                </button>

                <p class="text-gray-500 text-xs mt-2 text-center">Dung lượng file tối đa 1 MB</p>
                <p class="text-gray-500 text-xs text-center">Định dạng: JPEG, PNG</p>
            </div>
        </div>
        <!-- Modal Thay đổi Email -->
        <div id="emailChangeModal"
            class="fixed inset-0 bg-opacity-50 flex items-center justify-center z-50 
            opacity-0 pointer-events-none transition-opacity duration-500 ease-in-out">
            <div class="bg-white rounded-lg shadow-lg w-full max-w-md mx-4 transform transition-transform duration-500 ease-in-out scale-95">
                <div class="p-4 md:p-6">
                    <h2 class="text-lg md:text-xl font-medium text-center mb-4 md:mb-6">THAY ĐỔI EMAIL</h2>

                    <div>
                        <div class="mb-4 md:mb-6">
                            <label class="block text-gray-700 text-sm mb-1 md:mb-2">Email mới</label>
                            <input
                                type="email"
                                id="emailInput"
                                class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm md:text-base"
                                placeholder="Nhập địa chỉ email mới" />
                        </div>

                        <div class="mb-4 md:mb-6">
                            <div class="flex gap-4">
                                <button
                                    type="button"
                                    id="emailMethodBtn"
                                    class="flex-1 py-2 md:py-3 px-4 border rounded-md flex items-center justify-center border-blue-500 bg-blue-50 text-sm md:text-base">
                                    <svg class="w-5 h-5 md:w-6 md:h-6 mr-2 text-blue-500" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <rect x="2" y="2" width="20" height="20" rx="4" stroke="currentColor" stroke-width="2" />
                                        <path d="M6 9L12 12.5L18 9" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                        <path d="M6 18H18V9L12 12.5L6 9V18Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                    Gửi OTP
                                </button>
                            </div>
                        </div>

                        <div class="mb-6 md:mb-8">
                            <label class="block text-gray-700 text-sm mb-1 md:mb-2">Mã xác nhận OTP</label>
                            <input
                                type="text"
                                id="emailOtpInput"
                                class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm md:text-base"
                                placeholder="6 ký tự"
                                maxlength="6" />
                        </div>

                        <div class="space-y-2 md:space-y-3">
                            <button
                                id="emailConfirmBtn"
                                class="w-full py-2 md:py-3 bg-gray-200 rounded-md font-medium text-gray-700 cursor-not-allowed text-sm md:text-base"
                                disabled>
                                Xác nhận
                            </button>
                            <button
                                id="emailCancelBtn"
                                class="w-full py-2 md:py-3 border border-red-500 rounded-md font-medium text-red-500 text-sm md:text-base">
                                Trở về
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal Thay đổi Số điện thoại -->
        <div id="phoneChangeModal"
            class="fixed inset-0 bg-opacity-50 flex items-center justify-center z-50 
            opacity-0 pointer-events-none transition-opacity duration-500 ease-in-out">
            <div class="bg-white rounded-lg shadow-lg w-full max-w-md mx-4 transform transition-transform duration-500 ease-in-out scale-95">
                <div class="p-4 md:p-6">
                    <h2 class="text-lg md:text-xl font-medium text-center mb-4 md:mb-6">THAY ĐỔI SỐ ĐIỆN THOẠI</h2>

                    <div>
                        <div class="mb-4 md:mb-6">
                            <label class="block text-gray-700 text-sm mb-1 md:mb-2">Số điện thoại</label>
                            <input
                                type="tel"
                                id="phoneInput"
                                class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm md:text-base"
                                placeholder="Nhập số điện thoại" />
                        </div>

                        <div class="mb-4 md:mb-6">
                            <label class="block text-gray-700 text-sm mb-1 md:mb-2">Chọn phương thức xác minh OTP</label>
                            <div class="flex flex-col sm:flex-row gap-2 md:gap-4">
                                <button
                                    type="button"
                                    id="smsMethodBtn"
                                    class="flex-1 py-2 md:py-3 px-2 md:px-4 border rounded-md flex items-center justify-center border-blue-500 bg-blue-50 text-sm md:text-base">
                                    <svg class="w-5 h-5 md:w-6 md:h-6 mr-2 text-blue-500" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M20 2H4C2.9 2 2 2.9 2 4V22L6 18H20C21.1 18 22 17.1 22 16V4C22 2.9 21.1 2 20 2Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                    Tin nhắn SMS
                                </button>
                                <button
                                    type="button"
                                    id="zaloMethodBtn"
                                    class="flex-1 py-2 md:py-3 px-2 md:px-4 border rounded-md flex items-center justify-center border-gray-300 text-sm md:text-base">
                                    <svg class="w-5 h-5 md:w-6 md:h-6 mr-2 text-blue-500" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <rect x="2" y="2" width="20" height="20" rx="4" stroke="currentColor" stroke-width="2" />
                                        <path d="M12 8L16 12L12 16" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                        <path d="M8 12H16" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                    Zalo ZNS
                                </button>
                            </div>
                        </div>

                        <div class="mb-6 md:mb-8">
                            <label class="block text-gray-700 text-sm mb-1 md:mb-2">Mã xác nhận OTP</label>
                            <input
                                type="text"
                                id="otpInput"
                                class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm md:text-base"
                                placeholder="6 ký tự"
                                maxlength="6" />
                        </div>

                        <div class="space-y-2 md:space-y-3">
                            <button
                                id="confirmBtn"
                                class="w-full py-2 md:py-3 bg-gray-200 rounded-md font-medium text-gray-700 cursor-not-allowed text-sm md:text-base"
                                disabled>
                                Xác nhận
                            </button>
                            <button
                                id="cancelBtn"
                                class="w-full py-2 md:py-3 border border-red-500 rounded-md font-medium text-red-500 text-sm md:text-base">
                                Trở về
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>