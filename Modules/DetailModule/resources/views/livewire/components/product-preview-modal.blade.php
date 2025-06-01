<div>
    @if($data->preview)
        <div class="mt-4">
            <button
                class="w-full bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition-colors flex items-center justify-center gap-2"
                wire:click="openPreviewModal({{ $data->id }})">
                📖 Đọc thử
            </button>
        </div>
    @endif

    @if($isOpen)
        <div class="fixed inset-0 bg-black bg-opacity-90 z-50 flex items-center justify-center"
            wire:click.self="closeModal">
            <div class="bg-white rounded-none w-full h-full max-w-none flex flex-col overflow-hidden"
                onclick="event.stopPropagation()">
                <div class="flex justify-between items-center p-3 border-b bg-gray-50 flex-shrink-0">
                    <h3 class="text-lg font-semibold text-gray-800">
                        {{ $productTitle ?? 'Xem trước sản phẩm' }}
                    </h3>
                    <button wire:click="closeModal" class="text-gray-500 hover:text-gray-700 p-1">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <div class="flex-1 bg-gray-100 overflow-hidden">
                    @if($previewUrl)
                        <iframe src="{{ asset('pdfjs/web/viewer.html') }}?file={{ urlencode($previewUrl) }}"
                            class="w-full h-full border-none" frameborder="0" allowfullscreen>
                        </iframe>
                    @else
                        <div class="flex items-center justify-center h-full">
                            <p class="text-gray-600">Không tìm thấy file xem trước.</p>
                        </div>
                    @endif
                </div>

                <div class="p-3 border-t bg-gray-50 flex justify-end flex-shrink-0">
                    <button wire:click="closeModal"
                        class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600 transition-colors">
                        Đóng
                    </button>
                </div>
            </div>
        </div>
    @endif

</div>