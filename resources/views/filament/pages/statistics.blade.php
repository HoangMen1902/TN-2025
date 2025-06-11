<x-filament-panels::page>
  <div class="flex flex-col  gap-6 mt-6">
 
  <div class="flex w-full gap-4">
   
    <div class="w-1/4 bg-white p-4 rounded-xl border">
      <p class="text-gray-800 font-semibold text-sm">Sản phẩm</p>
      <h2 class="text-2xl font-bold mt-2">{{ $this->getProductCount() }}</h2>
      <p class="text-sm text-red-500 mt-1">-3.65% <span class="text-gray-600">so với tuần trước</span></p>
    </div>

   
    <div class="w-1/4 bg-white p-4 rounded-xl border">
      <p class="text-gray-800 font-semibold text-sm">Nhà xuất bản</p>
      <h2 class="text-2xl font-bold mt-2">{{ $this->getPublisherCount() }}</h2>
      <p class="text-sm text-green-500 mt-1">+5.25% <span class="text-gray-600">so với tuần trước</span></p>
    </div>

    <div class="w-1/4 bg-white p-4 rounded-xl border">
      <p class="text-gray-800 font-semibold text-sm">Mã giảm giá</p>
      <h2 class="text-2xl font-bold mt-2">{{ $this->getVoucherCount() }}</h2>
     <p class="text-sm {{ $this->getVoucherChangePercent() < 0 ? 'text-red-500' : 'text-green-500' }} mt-1">
    {{ $this->getVoucherChangePercent() > 0 ? '+' : '' }}{{ $this->getVoucherChangePercent() }}% 
    <span class="text-gray-600">so với tuần trước</span>
</p>
    </div>

   
    <div class="w-1/4 bg-white p-4 rounded-xl border">
      <p class="text-gray-800 font-semibold text-sm">Đơn hàng</p>
      <h2 class="text-2xl font-bold mt-2">{{ $this->getOrderCount() }}</h2>
      <p class="text-sm {{ $this->getOrderChangePercent() < 0 ? 'text-red-500' : 'text-green-500' }} mt-1">
    {{ $this->getOrderChangePercent() > 0 ? '+' : '' }}{{ $this->getOrderChangePercent() }}% 
    <span class="text-gray-600">so với tuần trước</span>
</p>

    </div>  

    
    <div class="w-1/4 bg-white p-4 rounded-xl border">
      <p class="text-gray-800 font-semibold text-sm">Doanh thu</p>
      <h2 class="text-2xl font-bold mt-2">{{ number_format($this->getRevenue(), 0) }} VNĐ</h2>
      <p class="text-sm text-green-500 mt-1">+6.65% <span class="text-gray-600">so với tuần trước</span></p>
    </div>
  </div>

 
  <div class="w-full xl:w-6/12">
    <div class="bg-white p-6 rounded-xl border h-full flex flex-col">
      <h3 class="text-lg font-semibold mb-4">📊 Đơn hàng theo ngày</h3>
      <canvas id="ordersChart" class="w-full h-72 grow"></canvas>
    </div>
  </div>
</div>

</x-filament-panels::page>

@push('scripts')
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      const ctx = document.getElementById('ordersChart').getContext('2d');
      const chart = new Chart(ctx, {
        type: 'line',
        data: {labels: {!! $this->getOrderChartData()->pluck('date')->map(fn($d) => \Carbon\Carbon::parse($d)->format('d/m'))->toJson() !!},
          datasets: [{
            label: 'Số đơn hàng',
            data: {!! $this->getOrderChartData()->pluck('total')->toJson() !!},
            borderColor: 'rgba(59, 130, 246, 1)',
            backgroundColor: 'rgba(59, 130, 246, 0.1)',
            fill: true,
            tension: 0.4,
            pointRadius: 3
          }]
        },
        options: {
          responsive: true,
          plugins: {
            legend: { display: true }
          },
          scales: {
  y: {
    beginAtZero: true,
    ticks: {
      stepSize: 1,       
      precision: 0      
    }
  }
}

        }
      });
    });
  </script>
@endpush