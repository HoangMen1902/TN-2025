<div class="container max-w-[1200px] mx-auto bg-white rounded-lg mb-6 shadow-md">
  <div class="bg-[#f4f9ff] h-screen flex flex-col items-center justify-center font-sans gap-5">
  <h1 class="text-2xl font-bold text-blue-700 mb-4">🎡 Vòng Quay May Mắn</h1>
    <div class="relative w-[500px] h-[500px] mb-6">

      <div id="wheelWrapper" class="relative w-full h-full">
        <div id="wheel" class="relative w-full h-full rounded-full border-[14px] border-gray-800 overflow-hidden">
          <div id="prizeLabels" class="absolute w-full h-full top-0 left-0"></div>
          <script>
            const prizes = @json($prizes);
          </script>
        </div>

        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 z-20">
          <div class="pointer"></div>

          <button id="spin"
            class="w-[80px] h-[80px] rounded-full border-[4px] border-white-400 bg-blue-600 text-white text-sm hover:bg-blue-700 transition disabled:bg-gray-500">
            Quay
          </button>
        </div>
      </div>
    </div>

    <!-- Modal thông báo -->
    <div id="prizeModal" class="fixed inset-0 bg-black/60 hidden items-center justify-center z-50">
      <div class="bg-white rounded-lg max-w-md w-4/5 p-6 text-center text-xl font-semibold relative">
        <span class="close absolute top-2 right-4 text-2xl cursor-pointer">×</span>
        <p id="prizeText"></p>
      </div>
    </div>
    <div class="mt-10 bg-white shadow-md rounded-lg p-4 max-w-lg mx-auto text-gray-700">
  <h2 class="text-xl font-semibold text-blue-600 mb-2">🎯 Quy Tắc Tham Gia</h2>
  <ul class="list-disc list-inside space-y-1 text-sm leading-relaxed">
    <li>Mỗi người chơi có 1 lượt quay mỗi ngày.</li>
    <li>Phần thưởng sẽ hiển thị ngay sau khi quay.</li>
    <li>Vui lòng không reload khi đang quay để tránh mất lượt.</li>
    <li>Hệ thống không hỗ trợ đổi thưởng nếu thoát giữa chừng.</li>
  </ul>
</div>

  </div>

  <!-- CSS đặt bên dưới -->
<style>
  @keyframes fadeIn {
    from {
      opacity: 0;
      transform: scale(0.9);
    }
    to {
      opacity: 1;
      transform: scale(1);
    }
  }

  @keyframes pulseGlow {
    0% {
      box-shadow: 0 0 20px rgba(59, 130, 246, 0.4);
    }
    50% {
      box-shadow: 0 0 40px rgba(59, 130, 246, 0.8);
    }
    100% {
      box-shadow: 0 0 20px rgba(59, 130, 246, 0.4);
    }
  }

  #wheel {
    background-image: conic-gradient(
      #e0f2fe 0deg 45deg,
      #bae6fd 45deg 90deg,
      #7dd3fc 90deg 135deg,
      #38bdf8 135deg 180deg,
      #0ea5e9 180deg 225deg,
      #0284c7 225deg 270deg,
      #0369a1 270deg 315deg,
      #075985 315deg 360deg
    );
    transition: transform 5s cubic-bezier(0.23, 1, 0.32, 1);
    box-shadow: 0 0 60px rgba(59, 130, 246, 0.5);
    border: 12px solid #e0f2fe;
  }

  .pointer {
    width: 0;
    height: 0;
    border-left: 12px solid transparent;
    border-right: 12px solid transparent;
    border-bottom: 30px solid #ffffff;
    position: absolute;
    top: -25px;
    left: 50%;
    transform: translateX(-50%);
    z-index: 30;
  }

  .prize-label {
    position: absolute;
    width: 100px;
    text-align: center;
    font-size: 14px;
    font-weight: bold;
    color: #1e3a8a;
    background-color: rgba(255, 255, 255, 0.85);
    border: 2px solid #3b82f6;
    padding: 3px 5px;
    border-radius: 5px;
    top: 45%;
    left: 40%;
    transform-origin: center center;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.15);
  }

  #spin {
    width: 80px;
    height: 80px;
    border-radius: 9999px;
    background-color: #2563eb;
    color: white;
    font-weight: bold;
    transition: background-color 0.3s ease;
    animation: pulseGlow 2s infinite;
  }

  #spin:hover {
    background-color: #1d4ed8;
  }

  #wheelWrapper {
    filter: drop-shadow(0 0 20px rgba(59, 130, 246, 0.25));
  }
</style>

</div>
