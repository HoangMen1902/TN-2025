    <div class="mx-auto py-4 md:py-8 px-4">
        <div class="md:hidden mb-6">
            <div class="w-full flex flex-col items-center">
                <div class="w-24 h-24 md:w-32 md:h-32 bg-gray-200 rounded-full flex items-center justify-center overflow-hidden">
                    <img id="avatarPreview-mobile"
                        src="{{ $avatar ? asset('storage/' . $avatar) : '' }}"
                        alt="Avatar"
                        class="{{ $avatar ? 'w-full h-full object-cover rounded-full' : 'hidden' }}" />
                    <svg id="avatarIcon-mobile" xmlns="http://www.w3.org/2000/svg"
                        class="{{ $avatar ? 'hidden' : 'h-16 w-16 text-gray-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                </div>

                <input type="file" accept="image/jpeg,image/png" wire:model="newAvatar" id="avatarInput" class="hidden" onchange="previewAvatar(event)" />

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
                            <input type="text" wire:model.defer="username" class="w-full border border-gray-300 rounded px-3 py-1.5 md:py-2 text-sm md:text-base">
                            @error('username') <p class="text-red-500 text-xs md:ml-1 mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <!-- Tên -->
                    <div class="mb-3 md:mb-4 flex flex-col md:flex md:flex-row md:items-center">
                        <label class="w-full md:w-1/3 text-gray-600 text-sm md:text-right pr-0 md:pr-4 mb-1 md:mb-0">Họ và Tên</label>
                        <div class="w-full md:w-2/3">
                            <input type="text" wire:model.defer="name" class="w-full border border-gray-300 rounded px-3 py-1.5 md:py-2 text-sm md:text-base">
                            @error('name') <p class="text-red-500 text-xs md:ml-1 mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <!-- Email -->
                    <div class="mb-3 md:mb-4 flex flex-col md:flex md:flex-row md:items-center">
                        <label class="w-full md:w-1/3 text-gray-600 text-sm md:text-right pr-0 md:pr-4 mb-1 md:mb-0">Email</label>
                        <div class="w-full md:w-2/3">
                            <div class="flex flex-row items-center">
                                <span class="border border-gray-300 rounded px-3 py-1.5 md:py-2 flex-grow text-sm md:text-base">{{ $email }}</span>
                                <button href="#" wire:click.prevent="openEmailModal" class="text-blue-500 ml-2 py-1.5 md:py-2 whitespace-nowrap text-sm md:text-base">Thay Đổi</button>
                            </div>
                        </div>
                    </div>

                    <!-- Số điện thoại -->
                    <div class="mb-3 md:mb-4 flex flex-col md:flex md:flex-row md:items-center">
                        <label class="w-full md:w-1/3 text-gray-600 text-sm md:text-right pr-0 md:pr-4 mb-1 md:mb-0">Số điện thoại</label>
                        <div class="w-full md:w-2/3">
                            <div class="flex flex-row items-center">
                                <input type="tel" wire:model.defer="phone" class="w-full border border-gray-300 rounded px-3 py-1.5 md:py-2 text-sm md:text-base">
                            </div>
                            @error('phone') <p class="text-red-500 text-xs md:ml-1 mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <!-- Giới tính -->
                    <div class="mb-3 md:mb-4 flex flex-col md:flex-row md:items-start">
                        <label class="w-full md:w-1/3 text-gray-600 text-sm md:text-right pr-0 md:pr-4 mb-1 md:mb-0">Giới tính</label>

                        <div class="w-full md:w-2/3">
                            <div class="flex flex-wrap gap-4">
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
                            @error('gender')
                            <p class="text-red-500 text-xs mt-1 block">{{ $message }}</p>
                            @enderror
                        </div>
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
                @if (session('success'))
                <div class="mb-4 text-sm text-green-700  p-3 rounded xl:text-center ">
                    {{ session('success') }}
                </div>
                @endif
            </div>


            <div class="hidden md:flex md:flex-col md:w-1/3 md:items-center">
                <div class="w-32 h-32 bg-gray-200 rounded-full flex items-center justify-center overflow-hidden">
                    <img id="avatarPreview"
                        src="{{ $avatar ? asset('storage/' . $avatar) : '' }}"
                        alt="Avatar"
                        class="{{ $avatar ? 'w-full h-full object-cover rounded-full' : 'hidden' }}" />
                    <svg id="avatarIcon" xmlns="http://www.w3.org/2000/svg" class="{{ $avatar ? 'hidden' : 'h-16 w-16 text-gray-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                </div>

                <input type="file" accept="image/jpeg,image/png" wire:model="newAvatar" id="avatarInput" class="hidden" onchange="previewAvatar(event)" />

                <button onclick="document.getElementById('avatarInput').click()" class="mt-4 px-4 py-2 border border-gray-300 rounded hover:bg-gray-100">
                    Chọn Ảnh
                </button>

                <p class="text-gray-500 text-xs mt-2 text-center">Dung lượng file tối đa 1 MB</p>
                <p class="text-gray-500 text-xs text-center">Định dạng: JPEG, PNG</p>
            </div>
        </div>
        <!-- Modal thay đổi email -->
        <div
            class="fixed inset-0 bg-opacity-50 flex items-center justify-center z-50
    transition-opacity duration-500 ease-in-out
    {{ $showEmailModal ? 'opacity-100 pointer-events-auto' : 'opacity-0 pointer-events-none' }}">

            <div
                class="bg-white rounded-lg shadow-lg w-full max-w-md mx-4 transform transition-transform duration-500 ease-in-out
        {{ $showEmailModal ? 'scale-100' : 'scale-95' }}">
                <div class="p-4 md:p-6">
                    <h2 class="text-lg md:text-xl font-medium text-center mb-4 md:mb-6">THAY ĐỔI EMAIL</h2>

                    @if (session('error'))
                    <div class="mb-4 text-sm text-red-700  p-3 rounded">
                        {{ session('error') }}
                    </div>
                    @endif

                    @if (session('success'))
                    <div class="mb-4 text-sm text-green-700  p-3 rounded">
                        {{ session('success') }}
                    </div>
                    @endif

                    <div class="space-y-4">
                        <!-- Email mới -->
                        <div>
                            <label class="block text-sm text-gray-700 mb-1">Email mới</label>
                            <input type="email" wire:model="newEmail"
                                class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm md:text-base"
                                placeholder="Nhập email mới" />
                            @error('newEmail')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Gửi OTP -->
                        <div>
                            <button wire:click="sendOtp" wire:loading.attr="disabled"
                                class="w-full flex justify-center items-center py-2 px-4 border rounded-md border-blue-500 bg-blue-50 hover:bg-blue-100 text-sm md:text-base text-blue-600 transition">
                                <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <rect x="2" y="2" width="20" height="20" rx="4" stroke-width="2"></rect>
                                    <path d="M6 9L12 12.5L18 9" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                                    <path d="M6 18H18V9L12 12.5L6 9V18Z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                                </svg>
                                Gửi OTP
                            </button>
                            @if ($otpSent)
                            <p class="text-green-600 text-sm mt-2 text-center">Mã OTP đã được gửi đến {{ $newEmail }}</p>
                            @endif
                        </div>

                        <!-- Nhập OTP -->
                        <div>
                            <label class="block text-sm text-gray-700 mb-1">Mã OTP</label>
                            <input type="text" wire:model.lazy="otp" maxlength="6"
                                class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm md:text-base"
                                placeholder="Nhập mã OTP" />
                            @error('otp')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Nút xác nhận & trở về -->
                        <div class="space-y-2 md:space-y-3">
                            <button wire:click="save" wire:loading.attr="disabled"
                                class="w-full py-2 md:py-3 rounded-md font-medium text-sm md:text-base transition
                        {{ empty($otp) ? 'bg-gray-200 text-gray-700 cursor-not-allowed' : 'bg-blue-600 text-white hover:bg-blue-700' }}"
                                @disabled(empty($otp))>
                                Xác nhận thay đổi
                            </button>

                            <button wire:click="closeEmailModal"
                                class="w-full py-2 md:py-3 border border-red-500 rounded-md font-medium text-red-500 text-sm md:text-base hover:bg-red-50">
                                Hủy bỏ
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>