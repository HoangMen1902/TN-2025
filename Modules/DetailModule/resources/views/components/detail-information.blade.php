<div class="detail-information p-4 mt-3">
    <h1 class="text-xl font-bold">Thông tin chi tiết</h1>
    <table class="mt-4">
        <tbody>
            <tr>
                <th class="text-sm font-light">Mã hàng</th>
                <td class="text-sm">8935088555321</td>
            </tr>
            <tr>
                <th class="text-sm font-light">Tên Nhà Cung Cấp</th>
                <td class="text-sm">
                    Cty Văn Hóa Minh Lâm</td>
            </tr>
            <tr>
                <th class="text-sm font-light">Tác giả</th>
                <td class="text-sm">
                    @if ($type === "product")
                        {{$data->author}}
                    @elseif ($type === "combo")
                        {{ $data->productSkus->pluck('product.author')->unique()->implode(', ') }}
                    @endif
                </td>
            </tr>
            <tr>
                <th class="text-sm font-light">NXB</th>
                <td class="text-sm">
                    @if ($type === "product")
                        {{$data->publisher->publisher_name}}
                    @elseif ($type === "combo")
                        @foreach ($data->productSkus->pluck('product.publisher.publisher_name')->unique() as $publisher)
                            {{ $publisher }}
                        @endforeach
                    @endif
                </td>
            </tr>
            @if ($type === "product")
                <tr>
                    <th class="text-sm font-light">Năm XB</th>
                    <td class="text-sm">{{$data->product_released_year}}</td>
                </tr>
            @endif
            <tr>
                <th class="text-sm font-light">Trọng lượng (gr)</th>
                <td class="text-sm">{{$data->weight}}</td>
            </tr>

            <tr>
                <th class="text-sm font-light">Kích Thước Bao Bì</th>
                <td class="text-sm">{{$data->width}} x {{$data->length}} x {{$data->height}} cm</td>
            </tr>
            @if ($type === "product")
                <tr>
                    <th class="text-sm font-light">Số trang</th>
                    <td class="text-sm">{{$data->pages}}</td>
                </tr>
            @endif

            <tr>
                <th class="text-sm font-light">Hình thức</th>
                <td class="text-sm">
                    @if ($type === "product")
                        {{$data->book_cover}}
                    @elseif ($type === "combo")
                        {{ $data->productSkus->pluck('product.book_cover')->unique()->implode('/ ') }}
                    @endif
                </td>
            </tr>
        </tbody>

    </table>
    <p class="text-sm">
        Giá sản phẩm trên BeeBook.com đã bao gồm thuế theo luật hiện hành. Bên cạnh đó, tuỳ vào loại sản phẩm, hình
        thức và địa chỉ giao hàng mà có thể phát sinh thêm chi phí khác như Phụ phí đóng gói, phí vận chuyển, phụ
        phí hàng cồng kềnh,...
    </p>
    <p class="text-red-600 text-sm">Chính sách khuyến mãi trên BeeBook.com không áp dụng cho Hệ thống Nhà sách BeeBook
        trên toàn quốc</p>
</div>