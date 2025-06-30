<div class="container max-w-[1200px] mx-auto bg-white rounded-lg mb-6 shadow-md p-4">
  <div class="lucky-spin">
    <div class="wheel-container">

      <div class="wheel">
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

      <div class="arrow"></div>
      <button class="spin-button" id="spin">Spin</button>
    </div>
  </div>

  <div id="resultModal"
    class="modal hidden fixed inset-0 bg-white/10 backdrop-blur-sm flex items-center justify-center z-50">
    <div class="bg-white p-6 rounded-lg shadow-xl text-center max-w-md w-full">
      <h2 class="text-2xl font-semibold mb-4 text-green-600">🎉 Chúc mừng! 🎉</h2>
      <p class="text-lg mb-6">Bạn nhận được: <span id="modalResult" class="font-bold text-red-500"></span></p>
      <button id="closeModal" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">Đóng</button>
    </div>
  </div>


</div>
<script>
    let segments = @json($prizes); 
</script>
