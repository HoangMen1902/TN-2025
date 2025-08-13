@php
    use App\Enums\ViettelPostStatusEnum;
    use App\Enums\OrderStatusEnum;
@endphp
<x-layouts.layout>
    <x-slot name="styles">
        <link rel="stylesheet" href="//cdn.datatables.net/2.3.2/css/dataTables.dataTables.min.css">
        <link href="https://api.mapbox.com/mapbox-gl-js/v3.14.0/mapbox-gl.css" rel="stylesheet">
        <script src="https://api.mapbox.com/mapbox-gl-js/v3.14.0/mapbox-gl.js"></script>
    </x-slot>
    <div class="container mx-auto max-w-[1200px] my-3 py-6 h-auto px-8 bg-white rounded-lg">
        <div class="flex items-center justify-between">
            <h1 class="text-xl font-bold ">Chi tiết đơn hàng</h1>
            <a href="{{ url()->previous() ?? route('home') }}" class="text-lg text-blue-500 underline">Quay lại</a>
        </div>
        <table id="orderDetail" class="display">
            <thead>
                <tr>
                    <th>Tên sản phẩm</th>
                    <th>Số lượng</th>
                    <th>Thành tiền</th>
                    <th>Hình ảnh</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data->order->orderDetails as $order_detail)
                    <tr>
                        <td>{{ $order_detail?->sku?->product?->name ?? 'Null' }}</td>
                        <td>x{{$order_detail->quantity ?? 0}}</td>
                        <td>{{number_format($order_detail->price, 0, '.', '.')}}đ</td>
                        <td><img class="object-cover" width="75" height="75"
                                src="{{asset('storage/' . $order_detail?->sku?->product?->thumbnail)}}" alt=""></td>
                    </tr>
                @endforeach

            </tbody>
        </table>
        <div class="my-2 flex items-center justify-between">
            <h1> Tổng tiền: <span class="font-semibold">{{number_format($data->order->total_price, 0 ,',', '.')}}đ</span></h1>
            <h1> Phí vận chuyển (Chưa chiết khấu): <span class="font-semibold">{{number_format($data->order->shipment_price, 0, ',', '.')}}đ</span></h1>
            <h1> Chiết khấu: <span class="font-semibold">{{number_format($data->order->reduced_price, 0, ',', '.')}}đ</span></h1>
        </div>




        @php
            $shipment_unit = $data->shipment_unit;
            $order_status = $data->order->orders_status;
            $latest_data = $data?->order?->webhook()?->latest('created_at')?->first() ?? null;
            $current_location = $latest_data?->current_location ?? null;
        @endphp


        @if ($current_location)
            @if ($order_status === OrderStatusEnum::VanChuyen)
                <h1 class="font-bold text-xl mb-2">Vị trí hàng của bạn</h1>
                <iframe class="w-full h-[400px]" style="border:0" loading="lazy" allowfullscreen
                    referrerpolicy="no-referrer-when-downgrade"
                    src="https://www.google.com/maps/embed/v1/place?key={{$google_map}}
                    &q=ViettelPost, {{$current_location}}&zoom=18">
                </iframe>
            @endif

            <div class="mt-2">
                <div class="flex justify-between items-center">
                    <h1 class="font-semibold text-xl">Thông tin vận chuyển</h1>
                    <p>Đơn vị vận chuyển: <span class="font-semibold mt-4">{{$shipment_unit}}</span></p>
                </div>

                <div class="flex items-center justify-center">
                <ol class="relative text-gray-500 border-s border-gray-200 mt-4 ">
                    @foreach ($data->order->webhook()->orderBy('created_at', 'asc')->get() as $shipping_data)
                        @php
                            $status = ViettelPostStatusEnum::getDescription((int) $shipping_data->shipping_status)
                        @endphp
                        <li class="mb-10 ms-6">
                            <span
                                class="absolute flex items-center justify-center w-8 h-8 bg-green-200 rounded-full -start-4 ring-4 ring-white ">
                                <svg class="w-3.5 h-3.5 text-green-500 " aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                    fill="none" viewBox="0 0 16 12">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M1 5.917 5.724 10.5 15 1.5" />
                                </svg>
                            </span>
                            <h3 class="font-medium leading-tight">{{$shipping_data->created_at}}</h3>
                            <p class="text-sm">{{$shipment_unit === 'Viettel Post' ? ViettelPostStatusEnum::getDescription((int) $shipping_data->shipping_status) : '' ?? null}}</p>

                        </li>
                    @endforeach
                    </ol>
                </div>
            </div>
        @endif
        <div class="mt-2">
            <p class="text-lg">Trạng thái đơn hàng: <span class="font-semibold">{{ $data->order->orders_status }}</span>
            </p>
        </div>


    </div>
    <x-slot name="scripts">
        <script src="//cdn.datatables.net/2.3.2/js/dataTables.min.js"></script>
        <script>
            fetch('https://cdn.datatables.net/plug-ins/1.13.6/i18n/vi.json')
                .then(response => response.json())
                .then(vietnamese => {
                    new DataTable('#orderDetail', {
                        language: vietnamese
                    });
                })
                .catch(error => console.error('Lỗi tải ngôn ngữ:', error));
        </script>

    </x-slot>
</x-layouts.layout>