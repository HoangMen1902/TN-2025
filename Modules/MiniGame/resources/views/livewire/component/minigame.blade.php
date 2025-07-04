<div>
    <div class="container max-w-[1200px] mx-auto bg-white rounded-lg mb-6 shadow-md p-6">
    <!-- Tiêu đề -->
    <div class="text-center mb-6">
        <h1 class="text-3xl font-bold text-pink-600">🎡 Vòng Quay May Mắn 🎉</h1>
        <p class="text-gray-600 mt-2">
            Nhấn vào nút <span class="font-semibold text-blue-500">“Quay”</span> để có cơ hội nhận phần thưởng hấp dẫn!
        </p>
    </div>

    <!-- Vòng quay và nút -->
    <div class="lucky-spin">
        <div class="wheel-container">
            <!-- Vòng quay -->
            <div class="wheel" wire:ignore>
                @for ($i = 0; $i < 10; $i++)
                    @php
                        $segmentClass = 'segment-' . ($i + 1);
                        $prizeName = $prizes[$i] ?? 'No Prize';
                    @endphp
                    <div class="segment {{ $segmentClass }}">
                        <span>{{ $prizeName }}</span>
                    </div>
                @endfor
            </div>

            <!-- Mũi tên -->
            <div class="arrow"></div>

            <!-- Nút điều khiển -->
            <div class="flex gap-4 justify-center mt-6">
                <button id="spin" class="spin-button">Quay</button>
                <button id="historyBtn" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg font-medium">
                    Lịch sử phần thưởng
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal kết quả -->
<div id="resultModal" class="modal hidden fixed inset-0 bg-white/10 backdrop-blur-sm flex items-center justify-center z-50" wire:ignore>
    <div class="bg-white p-6 rounded-xl shadow-xl text-center w-full max-w-md border border-gray-300">
        <h2 class="text-2xl font-bold mb-4 text-green-600">🎉 Chúc mừng! 🎉</h2>
        <p class="text-lg mb-6">
            Bạn nhận được: <span id="modalResult" class="font-bold text-red-500"></span>
        </p>
        <button id="closeModal" class="bg-blue-500 hover:bg-blue-600 text-white px-5 py-2 rounded-lg font-medium">
            Đóng
        </button>
    </div>
</div>

<!-- Modal lịch sử -->
<div id="historyModal" class="modal hidden fixed inset-0 bg-white/10 backdrop-blur-sm flex items-center justify-center z-50" wire:ignore>
    <div class="bg-white p-6 rounded-xl shadow-xl text-center w-full max-w-md border border-gray-300">
        <h2 class="text-2xl font-bold mb-4 text-blue-600">🎁 Lịch sử phần thưởng 🎁</h2>
        @if(count($history))
            <ul class="text-left max-h-[300px] overflow-y-auto px-2">
                @foreach ($history as $item)
                    <li class="mb-2">• {{ $item['name'] }} 
                        <span class="text-sm text-gray-500">({{ $item['won_at'] }})</span>
                    </li>
                @endforeach
            </ul>
        @else
            <p class="text-gray-500">Chưa có phần thưởng nào.</p>
        @endif
        <button id="closeHistoryModal" class="mt-6 bg-blue-500 hover:bg-blue-600 text-white px-5 py-2 rounded-lg font-medium">
            Đóng
        </button>
    </div>
</div>

<!-- Script biến truyền từ backend -->
<script>
    window.segments = @json($prizes);
</script>

</div>