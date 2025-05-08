

@section('content')
    <div class="wrapper">
        <div class="sidebar">
            <nav>
                <section class="filter-section">
                    <h3>Nhóm sản phẩm</h3>
                    <ul class="filter-list">
                        <li>Tất Cả Nhóm Sản Phẩm</li>
                        <li>Sách tiếng Việt</li>
                        <li>Văn học</li>
                        <li>Tiểu thuyết</li>
                        <li>Truyện ngắn - Tản Văn</li>
                        <li>Light Novel</li>
                        <li>Truyện Trinh Thám - Kiếm Hiệp</li>
                        <li>Tác Phẩm Kinh Điển</li>
                        <li>Huyền Bí - Giả Tưởng - Kinh Dị</li>
                        <li>Ngôn Tình</li>
                        <li>Thơ ca, tục ngữ, ca dao, thành ngữ</li>
                        <li class="show-more">Xem Thêm</li>
                    </ul>
                </section>

                <section class="filter-section">
                    <h3>Giá</h3>
                    <ul class="filter-list">
                        <li class="checkbox-item"><input type="checkbox" name="price" value="0-150000"> 0đ - 150,000đ</li>
                        <li class="checkbox-item"><input type="checkbox" name="price" value="150000-300000"> 150,000đ - 300,000đ</li>
                        <li class="checkbox-item"><input type="checkbox" name="price" value="300000-500000"> 300,000đ - 500,000đ</li>
                        <li class="checkbox-item"><input type="checkbox" name="price" value="500000-700000"> 500,000đ - 700,000đ</li>
                        <li class="checkbox-item"><input type="checkbox" name="price" value="700000+"> 700,000đ - trở lên</li>
                    </ul>
                </section>

                <section class="filter-section">
                    <h3>Nhà Cung Cấp</h3>
                    <ul class="filter-list">
                        <li class="checkbox-item"><input type="checkbox" name="supplier" value="nha-nam"> Nhã Nam</li>
                        <li class="checkbox-item"><input type="checkbox" name="supplier" value="nxb-tre"> NXB Trẻ</li>
                        <li class="checkbox-item"><input type="checkbox" name="supplier" value="dinh-ti"> Đinh Tị</li>
                        <li class="checkbox-item"><input type="checkbox" name="supplier" value="huy-hoang"> Huy Hoang Bookstore</li>
                        <li class="checkbox-item"><input type="checkbox" name="supplier" value="kim-dong"> Nhà Xuất Bản Kim Đồng</li>
                        <li class="checkbox-item"><input type="checkbox" name="supplier" value="tong-hop-tphcm"> NXB Tổng Hợp TPHCM</li>
                        <li class="checkbox-item"><input type="checkbox" name="supplier" value="bach-viet"> Bách Việt</li>
                        <li class="checkbox-item"><input type="checkbox" name="supplier" value="phu-nu"> Phụ Nữ</li>
                        <li class="show-more">Xem Thêm</li>
                    </ul>
                </section>

                <section class="filter-section">
                    <h3>Độ Tuổi</h3>
                    <ul class="filter-list">
                        <li class="checkbox-item"><input type="checkbox" name="age" value="11-15"> 11 - 15</li>
                        <li class="checkbox-item"><input type="checkbox" name="age" value="15-18"> 15 - 18</li>
                    </ul>
                </section>

                <section class="filter-section">
                    <h3>Ngôn Ngữ</h3>
                    <ul class="filter-list">
                        <li class="checkbox-item"><input type="checkbox" name="language" value="tieng-viet"> Tiếng Việt</li>
                        <li class="checkbox-item"><input type="checkbox" name="language" value="tieng-anh"> Tiếng Anh</li>
                    </ul>
                </section>

                <section class="filter-section">
                    <h3>Hình thức</h3>
                    <ul class="filter-list">
                        <li class="checkbox-item"><input type="checkbox" name="format" value="bia-mem"> Bìa Mềm</li>
                        <li class="checkbox-item"><input type="checkbox" name="format" value="bia-cung"> Bìa Cứng</li>
                    </ul>
                </section>

                <button class="select-button">Áp dụng bộ lọc</button>
            </nav>
        </div>

        <div class="main-content">
            <header class="sort-header">
                @include('productmodule::components.sort-header')
            </header>

            <section class="product-grid">
                {{-- @foreach ([['name' => 'Nhật Ký Đặng Thùy Trâm (Tái Bản 2022)', 'price' => 72000, 'original_price' => 90000, 'discount' => -20], ['name' => 'Hà Thanh Hải Yến - Ngang Qua Ngõ Nhỏ Bình An', 'price' => 147000, 'original_price' => 196000, 'discount' => -25], ['name' => 'Hồ Điệp Và Kình Ngư', 'price' => 111600, 'original_price' => 155000, 'discount' => -28], ['name' => 'Người Đàn Ông Mang Tên OVE (Tái Bản)', 'price' => 136000, 'original_price' => 160000, 'discount' => -15], ['name' => 'Trường Ca Achilles', 'price' => 124800, 'original_price' => 156000, 'discount' => -20]] as $product)
                    @include('productmodule::components.product-card', ['product' => $product])
                @endforeach --}}
            </section>

            <nav class="pagination">
                @include('productmodule::components.pagination')
            </nav>
        </div>
    </div>
@endsection