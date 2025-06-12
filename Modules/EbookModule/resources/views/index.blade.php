<x-layouts.layout>

    {{-- <div class="max-w-5xl mx-auto py-8" x-data="{ tab: 'text' }">
        <h1 class="text-2xl font-bold mb-4">{{ $ebook->product->name ?? 'Ebook' }}</h1>

        <div class="flex space-x-4 border-b mb-4">
            <button @click="tab = 'text'" :class="{ 'border-b-2 border-blue-500 font-semibold': tab === 'text' }">Nội
                dung</button>
            <button @click="tab = 'pdf'" :class="{ 'border-b-2 border-blue-500 font-semibold': tab === 'pdf' }">Xem
                PDF</button>
        </div>

        <div x-show="tab === 'text'"
            class="mt-4 whitespace-pre-wrap text-sm max-h-[600px] overflow-y-auto bg-gray-100 p-4 rounded">
            {{ $ebook->content ?? 'Không có nội dung' }}
        </div>
        <div x-show="tab === 'pdf'" class="mt-4">
            <iframe src="{{ asset('storage/' . $ebook->filepath) }}" width="100%" height="600px"
                class="rounded border"></iframe>
        </div>

    </div> --}}
    <div class="ebook-reader bg-gray-900 text-white overflow-hidden h-screen">
        <!-- Main Container -->
        <div class="ebook-container flex h-full">
            <!-- Sidebar -->
            <div class="ebook-sidebar w-80 bg-gray-800 border-r border-gray-700 flex flex-col">
                <!-- Header -->
                <div class="ebook-sidebar-header p-4 border-b border-gray-700">
                    <button class="ebook-back-btn mb-4 text-gray-400 hover:text-white">
                        <i class="fas fa-arrow-left text-lg"></i>
                    </button>
                    <div class="ebook-book-info flex items-start space-x-3">
                        <img src="data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNjAiIGhlaWdodD0iODAiIHZpZXdCb3g9IjAgMCA2MCA4MCIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPHJlY3Qgd2lkdGg9IjYwIiBoZWlnaHQ9IjgwIiByeD0iNCIgZmlsbD0iIzM3NDE1MSIvPgo8dGV4dCB4PSIzMCIgeT0iNDAiIGZpbGw9IiM5Q0E3QkEiIHRleHQtYW5jaG9yPSJtaWRkbGUiIGZvbnQtc2l6ZT0iMTIiPkJvb2s8L3RleHQ+Cjwvc3ZnPgo="
                            alt="Book Cover" class="ebook-cover w-16 h-20 rounded">
                        <div class="ebook-title-author">
                            <h2 class="ebook-title text-white font-semibold text-lg mb-1">Đẹp thế sao chim nói</h2>
                            <p class="ebook-author text-gray-400 text-sm">Khuynh An Noãn Hạ</p>
                        </div>
                    </div>
                </div>

                <!-- Book Stats -->
                <div class="ebook-stats p-4 border-b border-gray-700">
                    <div class="ebook-status text-orange-400 text-sm mb-3">
                        <i class="fas fa-book-open mr-2"></i>Sách hiệu sổi
                    </div>

                    <button
                        class="ebook-login-btn w-full bg-emerald-500 hover:bg-emerald-600 text-white py-3 px-4 rounded-lg font-medium mb-3 transition-colors">
                        Đăng nhập
                    </button>

                    <button
                        class="ebook-buy-btn w-full bg-gray-700 hover:bg-gray-600 text-white py-3 px-4 rounded-lg font-medium transition-colors">
                        Mua trọn bộ sách (7.740 sổi)
                    </button>
                </div>

                <!-- Description -->
                <div class="ebook-description flex-1 p-4 overflow-y-auto">
                    <div class="ebook-desc-text text-gray-300 text-sm leading-relaxed">
                        <p class="ebook-desc-para mb-3">Cô tên là A Da, là sát thủ hạng nhất của Nhật Tuyến Dưỡng, nhiệm
                            vụ gần nhất là âm sát anh vũ của vô làm minh chủ.</p>

                        <p class="ebook-desc-para mb-3">Ấy thế mà sở trời run rủi có lại xuyên ngược đến thời hiện đại,
                            trúng sinh vào cơ minh tình tuyên 18 Ôn Lường, kẻ tử tây có năng sát thủ bước trên con đường
                            trở thành đại làng giải trí, người gặp người sợ, vong gặp vong không dám theo.</p>

                        <p class="ebook-desc-para">Anh tên là Thượng Thần - boss của làng giải trí, ngay từ lần gặp đầu
                            tiên anh đã liếc mắt đưa tình với cô năng sát thủ, ấy thế mà... Cô lại nhìn chằm chằm đồng
                            mạch của anh, ái chà, nếu cứa một đường mạch của anh, ái chà, nếu cứa một đường</p>
                    </div>
                </div>
            </div>

            <!-- Main Reading Area -->
            <div class="flex-1 flex flex-col">
                <!-- Top Bar -->
                <div class="bg-gray-800 px-6 py-4 border-b border-gray-700 flex items-center justify-between">
                    <h1 class="text-xl font-semibold">Đẹp thế sao chim nói</h1>
                    <div class="text-sm text-gray-400">Tập 1 - Chương 1: Xuyên không</div>
                    <div class="flex items-center space-x-4">
                        <button class="text-gray-400 hover:text-white"><i class="fas fa-headphones"></i></button>
                        <button class="text-gray-400 hover:text-white"><i class="fas fa-list"></i></button>
                        <button class="text-gray-400 hover:text-white"><i class="fas fa-bookmark"></i></button>
                        <button class="text-gray-400 hover:text-white"><i class="fas fa-text-height"></i></button>
                        <button class="text-gray-400 hover:text-white"><i class="fas fa-expand"></i></button>
                    </div>
                </div>

                <!-- Reading Content -->
                <div class="flex-1 relative">
                    <!-- Navigation Buttons -->
                {{ $ebook->content ?? 'Không có nội dung' }}

                    {{-- <button
                        class="absolute left-4 top-1/2 transform -translate-y-1/2 w-10 h-10 bg-black bg-opacity-50 hover:bg-opacity-70 rounded-full flex items-center justify-center text-gray-300 hover:text-white transition-all z-10">
                        <i class="fas fa-chevron-left"></i>
                    </button>

                    <button
                        class="absolute right-4 top-1/2 transform -translate-y-1/2 w-10 h-10 bg-black bg-opacity-50 hover:bg-opacity-70 rounded-full flex items-center justify-center text-gray-300 hover:text-white transition-all z-10">
                        <i class="fas fa-chevron-right"></i>
                    </button>

                    <!-- Content Area -->
                    <div class="h-full overflow-y-auto px-16 py-12">
                        <div class="max-w-4xl mx-auto">
                            <!-- Chapter Title -->
                            <div class="text-center mb-12">
                                <h2 class="text-3xl font-light italic text-gray-300 mb-4">Chương 1</h2>
                                <h1 class="text-4xl font-bold text-white">XUYÊN KHÔNG</h1>
                            </div>

                            <!-- Reading Text -->
                            <div class="reading-content text-gray-200 text-lg space-y-6">
                                <p
                                    class="first-letter:text-6xl first-letter:font-bold first-letter:text-white first-letter:float-left first-letter:mr-2 first-letter:mt-1">
                                    Cơ thể đã thành thỏi quen, cô chưa mở mắt ra đã cảm nhận được nguy hiểm đang đến
                                    gần. Hơi thở xa lạ, mùi hương khác thường và hơi ấm lạ la lẫm. Dù đang nhắm mắt cô
                                    cũng có thể xác định được.</p>

                                <p>Có điều có không nóng với mà một mực chờ đợi, đợi người kia đến gần và nắm lấy cổ tay
                                    mình thì mới không chế đối phương.</p>

                                <p>Thân thể của cô vẫn nhanh nhẹn như xưa, trở tay một cái là đã tóm được cổ tay của kẻ
                                    đánh lén, cánh tay còn lại thì bóp chặt cổ họng của người kia.</p>

                                <p>Cô lạnh lùng đánh giá mọi thứ trước mặt. Là lẫm, thật sự là quá lạ lẫm, tất cả những
                                    gì có thể nhìn thấy điều không phải là thứ mà cô quen thuộc, bao gồm cả vũ khí trong
                                    tay người kia.</p>
                            </div>
                        </div>
                    </div> --}}
                </div>

                <!-- Bottom Progress Bar -->
                <div class="bg-gray-800 px-6 py-4 border-t border-gray-700 ">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm text-gray-400">Tập 1 - Chương 1: Xuyên không</span>
                        <span class="text-sm text-gray-400">20%</span>
                    </div>
                    <div class="w-full bg-gray-700 rounded-full h-2">
                        <div class="bg-white h-2 rounded-full" style="width: 20%"></div>
                    </div>
                </div>
            </div>
        </div>

        <script>
            // Simple navigation functionality
            document.addEventListener('DOMContentLoaded', function () {
                const leftNav = document.querySelector('.ebook-nav-prev');
                const rightNav = document.querySelector('.ebook-nav-next');
                const progressBar = document.querySelector('.ebook-progress-bar');
                const progressText = document.querySelector('.ebook-progress-percent');

                let currentProgress = 20;

                leftNav.addEventListener('click', function () {
                    if (currentProgress > 0) {
                        currentProgress -= 10;
                        updateProgress();
                    }
                });

                rightNav.addEventListener('click', function () {
                    if (currentProgress < 100) {
                        currentProgress += 10;
                        updateProgress();
                    }
                });

                function updateProgress() {
                    progressBar.style.width = currentProgress + '%';
                    progressText.textContent = currentProgress + '%';
                }
            });
        </script>
</x-layouts.layout>