<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $title ?? 'BeeBook'}}</title>

    @vite(['resources/scss/app.scss', 'resources/js/app.js', 'resources/css/app.css'])
</head>
{{ $styles ?? '' }}

<body>
<header class="main-header bg-white shadow-md py-2">
    <div class="container header-content max-w-[1200px] mx-auto flex flex-wrap items-center justify-between gap-4 px-4">
        <!-- Logo -->
        <div class="logo">
            <a href="/">
                <img src="{{ asset('assets/images/logongangtest.jpg') }}" alt="Logo" class="h-10 md:h-14 xl:h-20">
            </a>
        </div>

        <!-- Category Select (ẩn trên điện thoại) -->
        <div class="category-select hidden md:flex items-center gap-2 cursor-pointer">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                 stroke-width="1.5" stroke="currentColor" class="w-6 h-6 xl:w-10 xl:h-10">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M3.75 6A2.25 2.25 0 0 1 6 3.75h2.25A2.25 2.25 0 0 1 10.5 6v2.25a2.25 2.25 0 0 1-2.25 2.25H6a2.25 2.25 0 0 1-2.25-2.25V6ZM3.75 15.75A2.25 2.25 0 0 1 6 13.5h2.25a2.25 2.25 0 0 1 2.25 2.25V18a2.25 2.25 0 0 1-2.25 2.25H6A2.25 2.25 0 0 1 3.75 18v-2.25ZM13.5 6a2.25 2.25 0 0 1 2.25-2.25H18A2.25 2.25 0 0 1 20.25 6v2.25A2.25 2.25 0 0 1 18 10.5h-2.25a2.25 2.25 0 0 1-2.25-2.25V6ZM13.5 15.75a2.25 2.25 0 0 1 2.25-2.25H18a2.25 2.25 0 0 1 2.25 2.25V18A2.25 2.25 0 0 1 18 20.25h-2.25A2.25 2.25 0 0 1 13.5 18v-2.25Z" />
            </svg>
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                 stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="m19.5 8.25-7.5 7.5-7.5-7.5" />
            </svg>
        </div>

        <!-- Search -->
        <div class="search-wrapper flex flex-1 max-w-full md:max-w-[400px] xl:max-w-[500px]">
            <input type="text"
                   placeholder="Sách giải hỗ trợ học tập"
                   class="flex-grow px-4 py-2 border border-gray-300 rounded-l-md focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm xl:text-base">
            <button class="btn-search px-4 bg-blue-500 text-white rounded-r-md hover:bg-blue-600">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                     stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                </svg>
            </button>
        </div>

        <!-- Icons -->
        <div class="header-icons flex items-center gap-4">
            <div class="icon-item flex flex-col items-center text-xs xl:text-sm">
                <a href="#" class="flex flex-col items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 xl:w-6 xl:h-6" fill="none"
                         viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31..." />
                    </svg>
                    <span>Thông Báo</span>
                </a>
            </div>
            <div class="icon-item flex flex-col items-center text-xs xl:text-sm">
                <a href="#" class="flex flex-col items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 xl:w-6 xl:h-6" fill="none"
                         viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M2.25 3h1.386c.51 0..." />
                    </svg>
                    <span>Giỏ Hàng</span>
                </a>
            </div>
            <div class="icon-item flex flex-col items-center text-xs xl:text-sm">
                <a href="#" class="flex flex-col items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 xl:w-6 xl:h-6" fill="none"
                         viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M15.75 6a3.75 3.75 0 1 1-7.5 0..." />
                    </svg>
                    <span>Tài khoản</span>
                </a>
            </div>
        </div>
    </div>
</header>


    {{ $slot }}

    <footer class="page bg-white py-8 text-sm text-gray-700">
        <div class="container mx-auto px-4">
            <div class="flex gap-6 justify-between">
            <div class="w-full md:w-[25%] min-w-[250px] space-y-3 border-r border-gray-300 pr-4">

                    <img src="{{ asset('assets/images/logotest1.png') }}" alt="Beebook Logo" class="w-[150px]">
                    <p>
                        149/20 Đường 30/4, Phường Xuân Khánh, Quận Ninh Kiều, Cần Thơ<br>
                        Công Ty Cổ Phần Phát Hành Sách TP HCM - BeeBook<br>
                    </p>
                    <p>
                        Beebook.com nhận đặt hàng trực tuyến và giao hàng tận nơi.
                        KHÔNG hỗ trợ đặt mua và nhận hàng trực tiếp tại văn phòng
                        cũng như tất cả Hệ Thống Beebook trên toàn quốc.
                    </p>
                    <img src="{{ asset('assets/images/logo-bo-cong-thuong-da-thong-bao1.jpg') }}" alt="Đã thông báo BCT" class="w-[120px]">
                    <div class="flex items-center gap-2 mt-2">
                        <img src="{{ asset('assets/images/Facebook.jpg') }}" alt="Facebook" class="w-10">
                        <img src="{{ asset('assets/images/Instagram.jpg') }}" alt="Instagram" class="w-10">
                        <img src="{{ asset('assets/images/Youtube.jpg') }}" alt="YouTube" class="w-10">
                        <img src="{{ asset('assets/images/Twitter.jpg') }}" alt="Twitter" class="w-10">
                        <img src="{{ asset('assets/images/Tumblr.jpg') }}" alt="TikTok" class="w-10">
                    </div>
                    <div class="flex gap-2 mt-2">
                        <img src="{{ asset('assets/images/chplay.jpg') }}" alt="Google Play" class="h-8">
                        <img src="{{ asset('assets/images/appstore.jpg') }}" alt="App Store" class="h-8">
                    </div>
                </div>


                <div class="w-full md:w-[75%] min-w-[250px] space-y-3">
                    <div class=" flex w-full md:w-[100%] min-w-[250px] space-y-3">
                        <div class="w-full md:w-[33%] min-w-[200px]">
                            <h4 class="font-semibold mb-2">DỊCH VỤ</h4>
                            <ul class="space-y-1">
                                <li class="my-3"><a href="#">Điều khoản sử dụng</a></li>
                                <li class="my-3"><a href="#">Chính sách bảo mật thông tin cá nhân</a></li>
                                <li class="my-3"><a href="#">Chính sách bảo mật thanh toán</a></li>
                                <li class="my-3"><a href="#">Giới thiệu Beebook</a></li>
                                <li class="my-3"><a href="#">Hệ thống trung tâm - nhà sách</a></li>
                            </ul>
                        </div>
                        <div class="w-full md:w-[33%] min-w-[200px]">
                            <h4 class="font-semibold mb-2">HỖ TRỢ</h4>
                            <ul class="space-y-1">
                                <li class="my-3"><a href="#">Chính sách đổi - trả - hoàn tiền</a></li>
                                <li class="my-3"><a href="#">Chính sách bảo hành - bồi hoàn</a></li>
                                <li class="my-3"><a href="#">Chính sách vận chuyển</a></li>
                                <li class="my-3"><a href="#">Chính sách khách sỉ</a></li>
                            </ul>
                        </div>


                        <div class="w-full md:w-[33%] min-w-[200px]">
                            <h4 class="font-semibold mb-2">TÀI KHOẢN CỦA TÔI</h4>
                            <ul class="space-y-1">
                                <li class="my-3"><a href="#">Đăng nhập/Tạo mới tài khoản</a></li>
                                <li class="my-3"><a href="#">Thay đổi địa chỉ khách hàng</a></li>
                                <li class="my-3"><a href="#">Chi tiết tài khoản</a></li>
                                <li class="my-3"><a href="#">Lịch sử mua hàng</a></li>
                            </ul>
                        </div>
                    </div>


                    <div class="w-full mt-6 space-y-3">
                        <h4 class="font-semibold">LIÊN HỆ</h4>
                        <div class="flex ">
                            <p class="flex items-center w-full md:w-[33%] min-w-[200px]">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6 mr-2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" />
                                </svg>
                                60-62 Lê Lợi, Q.1, TP. HCM
                            </p>
                            <p class="flex items-center w-full md:w-[33%] min-w-[200px]">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6 mr-2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75" />
                                </svg>
                                cskh@beebook.com.vn
                            </p>
                            <p class="flex items-center w-full md:w-[33%] min-w-[200px]"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6 mr-2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 0 0 2.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 0 1-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 0 0-1.091-.852H4.5A2.25 2.25 0 0 0 2.25 4.5v2.25Z" />
                                </svg>
                                1900636467</p>
                        </div>

                        <div class="mt-5 flex gap-4 items-center">
                            <div class="w-full md:w-[33%] min-w-[200px] ">
                                <img src="{{ asset('assets/images/logo_lex.jpg') }}" alt="LEX" class="delivery-icon ">
                            </div>
                            <div class="w-full md:w-[33%] min-w-[200px] ">
                                <img src="{{ asset('assets/images/Logo_ninjavan.jpg') }}" alt="Ninja Van" class="delivery-icon ">
                            </div>
                            <div class="w-full md:w-[33%] min-w-[200px] ">
                                <img src="{{ asset('assets/images/vnpost.jpg') }}" alt="ViettelPost" class="delivery-icon ">
                            </div>
                        </div>

                        <div class="mt-20 flex  gap-4 items-center">
                            <div class="w-full md:w-25%] min-w-[200px] ">
                                <img src="{{ asset('assets/images/vnpay_log.jpg') }}" alt="VNPAY" class="payment-icon">
                            </div>
                            <div class="w-full md:w-25%] min-w-[200px] ">
                                <img src="{{ asset('assets/images/momopay.jpg') }}" alt="MoMo" class="payment-icon">
                            </div>
                            <div class="w-full md:w-25%] min-w-[200px] ">
                                <img src="{{ asset('assets/images/shopeepay_logo.jpg') }}" alt="ShopeePay" class="payment-icon">
                            </div>
                            <div class="w-full md:w-25%] min-w-[200px] ">
                                <img src="{{ asset('assets/images/logo_zalopay_2.png') }}" alt="ZaloPay" class="payment-icon">
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <p class=" justify-self-center text-xs mt-10">
                Giấy chứng nhận Đăng ký Kinh doanh số 0304132047 do Sở KHĐT TP.HCM cấp ngày 20/12/2005,
                đăng ký thay đổi lần thứ 10, ngày 07/05/2025.
            </p>
        </div>
    </footer>



    <!-- script -->
    {{ $scripts ?? ''}}
    <script src="https://code.jquery.com/jquery-3.7.1.slim.min.js" integrity="sha256-kmHvs0B+OpCW5GVHUNjv9rOmY0IvSIRcf7zGUDTDQM8=" crossorigin="anonymous"></script>
</body>

</html>