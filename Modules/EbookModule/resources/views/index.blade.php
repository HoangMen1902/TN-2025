<x-layouts.layout>
    <!-- FontAwesome và Alpine.js -->
    <head>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
        <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
        <!-- Fallback cho Alpine.js -->
        <script defer>
            if (!window.Alpine) {
                console.error('Alpine.js failed to load from CDN');
                document.write('<script src="/path/to/local/alpine.min.js"><\/script>');
            } else {
                console.log('Alpine.js loaded successfully');
            }
        </script>
        <style>
            /* Hiệu ứng chuyển đổi mượt mà cho sidebar */
            .ebook-sidebar {
                transition: width 0.3s ease-in-out;
            }
            .ebook-sidebar-collapsed {
                width: 0 !important;
                overflow: hidden;
            }
            /* Định dạng nút thu nhỏ sidebar trong Top Bar */
            .sidebar-toggle-btn {
                display: flex;
                align-items: center;
                justify-content: center;
                width: 2rem;
                height: 2rem;
            }
        </style>
    </head>

    <div class="ebook-reader bg-gray-900 text-white overflow-hidden h-screen" x-data="{
        tab: '{{ pathinfo($ebook->file_path, PATHINFO_EXTENSION) === 'text' ? 'pdf' : 'text' }}',
        fontSize: isNaN(parseInt(localStorage.getItem('ebookFontSize'))) ? 16 : parseInt(localStorage.getItem('ebookFontSize')),
        textAlign: localStorage.getItem('ebookTextAlign') || 'left',
        isSidebarCollapsed: false,
        increaseFont() {
            console.log('Increase font size clicked, new size:', this.fontSize + 2);
            this.fontSize += 2;
            localStorage.setItem('ebookFontSize', this.fontSize);
        },
        decreaseFont() {
            console.log('Decrease font size clicked, current size:', this.fontSize);
            if (this.fontSize > 12) {
                this.fontSize -= 2;
                localStorage.setItem('ebookFontSize', this.fontSize);
                console.log('New font size:', this.fontSize);
            } else {
                console.log('Font size not decreased, already at minimum (12px)');
            }
        },
        setAlign(align) {
            console.log('Set align clicked, new align:', align);
            this.textAlign = align;
            localStorage.setItem('ebookTextAlign', align);
        },
        toggleSidebar() {
            console.log('Toggle sidebar clicked, new state:', !this.isSidebarCollapsed);
            this.isSidebarCollapsed = !this.isSidebarCollapsed;
        }
    }" x-init="console.log('Alpine.js initialized, fontSize:', fontSize, 'textAlign:', textAlign, 'isSidebarCollapsed:', isSidebarCollapsed);">
        <!-- Main Container -->
        <div class="ebook-container flex h-full">
            <!-- Sidebar -->
            <div style="background-color: #3a3a3c" class="ebook-sidebar w-80 bg-gray-800 border-r  flex flex-col" :class="{ 'ebook-sidebar-collapsed': isSidebarCollapsed }">
                <!-- Header -->
                <div class="ebook-sidebar-header p-4 border-b " style="background-color: #3a3a3c">
                    {{-- <a href="{{ route('ebooks.index') }}" class="ebook-back-btn text-gray-400 hover:text-white">
                        <i class="fas fa-arrow-left text-lg"></i>
                    </a> --}}
                    <div class="ebook-book-info flex items-start space-x-3 mt-4">
                        <img src="{{ $ebook->cover_image ? asset('storage/' . $ebook->cover_image) : 'data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNjAiIGhlaWdodD0iODAiIHZpZXdCb3g9IjAgMCA2MCA4MCIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPHJlY3Qgd2lkdGg9IjYwIiBoZWlnaHQ9IjgwIiByeD0iNCIgZmlsbD0iIzM3NDE1MSIvPgo8dGV4dCB4PSIzMCIgeT0iNDAiIGZpbGw9IiM5Q0E3QkEiIHRleHQtYW5jaG9yPSJtaWRkbGUiIGZvbnQtc2l6ZT0iMTIiPkJvb2s8L3RleHQ+Cjwvc3ZnPgo=' }}"
                            alt="Book Cover" class="ebook-cover w-16 h-20 rounded">
                        <div class="ebook-title-author">
                            <h2 class="ebook-title text-white font-semibold text-lg mb-1">
                                {{ $ebook->product->name ?? $ebook->title }}
                            </h2>
                            <p class="ebook-author text-gray-400 text-sm">{{ $ebook->author }}</p>
                        </div>
                    </div>
                </div>

                <!-- Book Stats -->
                <div class="ebook-stats p-4 border-b " style="background-color: #3a3a3c">
                    <div class="ebook-status text-orange-400 text-sm mb-3">
                        <!-- <i class="fas fa-book-open mr-2"></i>Sách hiệu -->
                    </div>
                    <button
                        class="ebook-login-btn w-full bg-emerald-500 hover:bg-emerald-600 text-white py-3 px-4 rounded-lg font-medium mb-3 transition-colors">
                        {{-- Đăng nhập --}}
                        Mua trọn bộ sách ({{ number_format($ebook->price) }})
                        <a href="{{ route('ebook.payment', ['ebook_id' => $ebook->id]) }}" class="btn btn-primary">Thanh toán ngay</a>
                    </button>
                    {{-- <button
                        class="ebook-buy-btn w-full bg-gray-700 hover:bg-gray-600 text-white py-3 px-4 rounded-lg font-medium transition-colors">
                        Mua trọn bộ sách ({{ number_format($ebook->price) }})
                    </button> --}}
                </div>

                <!-- Chapters List -->
                <div class="ebook-chapters p-4 border-b " style="background-color: #3a3a3c">
                    <h3 class="text-lg font-semibold mb-3">Danh sách chương</h3>
                    <ul class="text-gray-300 text-sm space-y-2 max-h-64 overflow-y-auto">
                        @foreach ($chapters as $index => $chapter)
                            <li>
                                <a href="{{ route('ebooks.show', ['ebookId' => $ebook->id, 'chapter' => $index + 1]) }}"
                                    class="block p-2 hover:bg-gray-700 rounded {{ request()->query('chapter', 1) == $index + 1 ? 'bg-gray-700' : '' }}">
                                    {{ $chapter->chapter_name }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>

                <!-- Description -->
                <div class="ebook-description flex-1 p-4 overflow-y-auto">
                    <div class="ebook-desc-text text-gray-300 text-sm leading-relaxed">
                        <p class="ebook-desc-para mb-3">{{ $ebook->description ?? 'Không có mô tả' }}</p>
                    </div>
                </div>
            </div>

            <!-- Main Reading Area -->
            <div class="flex-1 flex flex-col">
                <!-- Top Bar -->
                <div class=" px-6 py-4 border-b  flex items-center justify-between" style="background-color: #3a3a3c">
                    <div class="flex items-center space-x-4">
                        <button class="sidebar-toggle-btn text-gray-400 hover:text-white z-10" @click="toggleSidebar()" :title="isSidebarCollapsed ? 'Mở sidebar' : 'Thu nhỏ sidebar'">
                            <i x-bind:class="isSidebarCollapsed ? 'fas fa-bars' : 'fas fa-arrow-left text-lg'"></i>
                        </button>
                        <h1 class="text-xl font-semibold">{{ $ebook->product->name ?? $ebook->title }}</h1>
                    </div>
                    <div class="text-sm text-gray-400">
                        @if ($chapters->isNotEmpty() && $chapters->count() >= request()->query('chapter', 1))
                            {{ $chapters[request()->query('chapter', 1) - 1]->chapter_name }}
                        @else
                            Chương 1
                        @endif
                    </div>
                    <div class="flex items-center space-x-4">
                        <button class="text-gray-400 hover:text-white z-10" @click="tab = 'text'; console.log('Text tab clicked')" title="Nội dung">
                            <i class="fas fa-book"></i>
                        </button>
                        @if (pathinfo($ebook->file_path, PATHINFO_EXTENSION) === 'pdf')
                            <button class="text-gray-400 hover:text-white z-10" @click="tab = 'pdf'; console.log('PDF tab clicked')" title="PDF">
                                <i class="fas fa-file-pdf"></i>
                            </button>
                        @endif
                        <button class="text-gray-400 hover:text-white z-10" @click="increaseFont()" title="Tăng cỡ chữ">
                            <i class="fas fa-plus"></i>
                        </button>
                        <button class="text-gray-400 hover:text-white z-10" @click="decreaseFont()" title="Giảm cỡ chữ">
                            <i class="fas fa-minus"></i>
                        </button>
                        <button class="text-gray-400 hover:text-white z-10" @click="setAlign('left')" title="Căn trái">
                            <i class="fas fa-align-left"></i>
                        </button>
                        <button class="text-gray-400 hover:text-white z-10" @click="setAlign('center')" title="Căn giữa">
                            <i class="fas fa-align-center"></i>
                        </button>
                        <button class="text-gray-400 hover:text-white z-10" @click="setAlign('right')" title="Căn phải">
                            <i class="fas fa-align-right"></i>
                        </button>
                    </div>
                </div>

                <!-- Reading Content -->
                <div class="flex-1 relative">
                    <!-- Tabs -->
                    {{-- <div class="flex space-x-4 border-b mb-4 px-6">
                        <button @click="tab = 'text'; console.log('Text tab button clicked')" :class="{ 'border-b-2 border-blue-500 font-semibold': tab === 'text' }">Nội dung</button>
                        @if (pathinfo($ebook->file_path, PATHINFO_EXTENSION) === 'pdf')
                            <button @click="tab = 'pdf'; console.log('PDF tab button clicked')" :class="{ 'border-b-2 border-blue-500 font-semibold': tab === 'pdf' }">Xem PDF</button>
                        @endif
                    </div> --}}

                    <!-- Content -->
                    <div x-show="tab === 'text'"
                         class=" whitespace-pre-wrap 
                         max-h-[700px] 
                         overflow-y-auto p-4 rounded bg-gray-100 text-gray-800"
                         :style="{ 'font-size': fontSize + 'px', 'text-align': textAlign }">
                        @if ($chapters->isNotEmpty() && $chapters->count() >= request()->query('chapter', 1))
                            @php
                                $chapter = $chapters[request()->query('chapter', 1) - 1];
                            @endphp
                            {!! $chapter->content ?? 'Không có nội dung' !!}
                        @else
                            Không có nội dung chương
                        @endif
                    </div>
                    <div x-show="tab === 'pdf'">
                        @if ($chapters->isNotEmpty() && $chapters->count() >= request()->query('chapter', 1))
                            @php
                                $chapter = $chapters[request()->query('chapter', 1) - 1];
                            @endphp
                            <iframe src="{{ asset('storage/' . $chapter->file_path) }}" width="100%" height="600px"
                                class="rounded border"></iframe>
                        @else
                            <div class="p-4 text-sm text-red-500">Không tìm thấy chương hoặc file PDF</div>
                        @endif
                    </div>

                    <!-- Navigation Buttons -->
                    @if ($chapters->isNotEmpty())
                        <button
                            class="absolute left-4 top-1/2 transform -translate-y-1/2 w-10 h-10 bg-black bg-opacity-50 hover:bg-opacity-70 rounded-full flex items-center justify-center text-gray-300 hover:text-white transition-all z-10"
                            @if (request()->query('chapter', 1) > 1)
                                onclick="window.location.href='{{ route('ebooks.show', ['ebookId' => $ebook->id, 'chapter' => request()->query('chapter', 1) - 1]) }}'"
                            @endif>
                            <i class="fas fa-chevron-left"></i>
                        </button>
                        <button
                            class="absolute right-4 top-1/2 transform -translate-y-1/2 w-10 h-10 bg-black bg-opacity-50 hover:bg-opacity-70 rounded-full flex items-center justify-center text-gray-300 hover:text-white transition-all z-10"
                            @if (request()->query('chapter', 1) < $chapters->count())
                                onclick="window.location.href='{{ route('ebooks.show', ['ebookId' => $ebook->id, 'chapter' => request()->query('chapter', 1) + 1]) }}'"
                            @endif>
                            <i class="fas fa-chevron-right"></i>
                        </button>
                    @endif
                </div>

                <!-- Bottom Progress Bar -->
                <div class="bg-gray-800 px-6 py-4 border-t " style="background-color: #3a3a3c">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm text-gray-400">
                            @if ($chapters->isNotEmpty() && $chapters->count() >= request()->query('chapter', 1))
                                {{ $chapters[request()->query('chapter', 1) - 1]->chapter_name }}
                            @else
                                Chương 1
                            @endif
                        </span>
                        <span class="text-sm text-gray-400">
                            {{ round((request()->query('chapter', 1) / max($chapters->count(), 1)) * 100) }}%
                        </span>
                    </div>
                    <div class="w-full bg-gray-700 rounded-full h-2">
                        <div class="bg-white h-2 rounded-full"
                            style="width: {{ round((request()->query('chapter', 1) / max($chapters->count(), 1)) * 100) }}%">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const progressBar = document.querySelector('.bg-white.h-2.rounded-full');
            const progressText = document.querySelector('.text-sm.text-gray-400:last-child');
            let currentProgress = {{ round((request()->query('chapter', 1) / max($chapters->count(), 1)) * 100) }};

            function updateProgress() {
                progressBar.style.width = currentProgress + '%';
                progressText.textContent = currentProgress + '%';
            }

            updateProgress();
        });
    </script>
</x-layouts.layout>
