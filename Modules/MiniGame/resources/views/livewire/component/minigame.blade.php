<div>
    <div class="container max-w-[1200px] mx-auto bg-white rounded-lg mb-6 shadow-md p-6">
        <!-- Tiêu đề -->
        <div class="text-center mb-6">
            <h1 class="text-3xl font-bold text-pink-600">🎡 Vòng Quay May Mắn 🎉</h1>
            <div class="text-center mt-4">
                <p class="text-lg font-semibold text-green-600">
                    Bạn còn <span class="text-blue-600">{{ $remainingSpins }}</span> lượt quay miễn phí hôm nay
                </p>
                @if ($nextSpinTime)
                    <p class="text-sm text-gray-500 mt-1">
                        Lượt tiếp theo sẽ có sau: <span id="countdown" class="font-semibold text-red-500">--:--:--</span>
                    </p>
                    <script>
                        document.addEventListener("DOMContentLoaded", function () {
                            const countdownEl = document.getElementById("countdown");
                            const targetTime = new Date("{{ $nextSpinTime }}").getTime();

                            const interval = setInterval(() => {
                                const now = new Date().getTime();
                                const distance = targetTime - now;

                                if (distance <= 0) {
                                    clearInterval(interval);
                                    countdownEl.innerText = "Đã sẵn sàng!";
                                    return;
                                }

                                const hours = String(Math.floor((distance / (1000 * 60 * 60)) % 24)).padStart(2, '0');
                                const minutes = String(Math.floor((distance / (1000 * 60)) % 60)).padStart(2, '0');
                                const seconds = String(Math.floor((distance / 1000) % 60)).padStart(2, '0');

                                countdownEl.innerText = `${hours}:${minutes}:${seconds}`;
                            }, 1000);
                        });
                    </script>
                @endif
            </div>

            <p class="text-gray-600 mt-2">
                Nhấn vào nút <span class="font-semibold text-blue-500">“Quay”</span> để có cơ hội nhận phần thưởng hấp
                dẫn!
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
                    <button id="spin" class="spin-button"
                        data-requires-login="{{ auth()->check() ? 'false' : 'true' }}">
                        Quay
                    </button>

                    <button id="historyBtn"
                        class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg font-medium">
                        Lịch sử phần thưởng
                    </button>
                </div>
            </div>
        </div>
       
<div class="mt-6 text-left bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded-md shadow-sm">
    <h3 class="text-xl font-bold text-yellow-700 mb-2">📜 Quy luật vòng quay</h3>
    <ul class="list-disc list-inside text-gray-700 text-sm leading-relaxed">
        <li>Mỗi ngày bạn có <span class="font-semibold text-green-600">2 lượt quay miễn phí</span>, cách nhau ít nhất 12 giờ.</li>
        <li>Khi hết lượt, bạn phải chờ đến lượt tiếp theo hoặc quay lại vào ngày hôm sau.</li>
        <li>Các phần thưởng được phân bổ ngẫu nhiên theo tỷ lệ xác suất.</li>
        <li>Kết quả sẽ được lưu trong <span class="text-blue-600 font-medium">lịch sử phần thưởng</span>.</li>
        <li>Hệ thống không hoàn lại lượt quay đã sử dụng.</li>
        <li>Vui lòng đăng nhập để tham gia vòng quay và nhận phần thưởng.</li>
    </ul>
</div>

    </div>

    <!-- Modal kết quả -->
    <div id="resultModal"
        class="modal hidden fixed inset-0 bg-white/10 backdrop-blur-sm flex items-center justify-center z-50"
        wire:ignore>
        <div class="bg-white p-6 rounded-xl shadow-xl text-center w-full max-w-md border border-gray-300">
           <h2 id="modalTitle" class="text-2xl font-bold mb-4 text-yellow-600">⚠️ Thông báo</h2>
<p id="modalText" class="text-lg mb-6 text-gray-700">
    <span id="modalResult" class="font-bold text-red-500"></span>
</p>

            <button id="closeModal" class="bg-blue-500 hover:bg-blue-600 text-white px-5 py-2 rounded-lg font-medium">
                Đóng
            </button>
        </div>
    </div>

    <!-- Modal lịch sử -->
    <div id="historyModal"
        class="modal hidden fixed inset-0 bg-white/10 backdrop-blur-sm flex items-center justify-center z-50"
        wire:ignore>
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
            <button id="closeHistoryModal"
                class="mt-6 bg-blue-500 hover:bg-blue-600 text-white px-5 py-2 rounded-lg font-medium">
                Đóng
            </button>
        </div>
    </div>

    <!-- Script biến truyền từ backend -->
    <script>
        window.segments = @json($prizes);
        window.remainingSpins = {{ $remainingSpins ?? 0 }};
        window.nextSpinTime = @json($nextSpinTime);
        window.isAdmin = @json(auth()->user()?->email === 'admin@admin.com');
    </script>

</div>