<x-filament-widgets::widget>
    <div class="p-4 bg-yellow-200 mb-4">Widget test hiển thị!</div>

    <div>
        <h3 class="font-bold mb-2">Top từ khóa tìm kiếm</h3>
        <ul>
            @forelse ($this->topKeywords as $keyword)
                <li>- {{ $keyword }}</li>
            @empty
                <li>Không có dữ liệu</li>
            @endforelse
        </ul>
    </div>

    <div class="mt-6">
        <h3 class="font-bold mb-2">Top loại sản phẩm đã mua</h3>
        <ul>
            @forelse ($this->topCategories as $category)
                <li>- {{ $category->name }} ({{ $category->products_count }})</li>
            @empty
                <li>Không có dữ liệu</li>
            @endforelse
        </ul>
    </div>

    {{-- Chart thử nghiệm --}}
    <div id="test-chart" class="mt-8"></div>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var options = {
                chart: {
                    type: 'bar',
                    height: 250
                },
                series: [{
                    name: 'Số lượng',
                    data: @json($this->topCategories->pluck('products_count'))
                }],
                xaxis: {
                    categories: @json($this->topCategories->pluck('name'))
                }
            };
            var chart = new ApexCharts(document.querySelector("#test-chart"), options);
            chart.render();
        });
    </script>
</x-filament-widgets::widget>