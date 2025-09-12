<x-layouts.layout>
    <section class="w-full overflow-x-hidden">
        <div class="relative w-full h-[500px] bg-cover bg-center" style="background-image: url('https://images.pexels.com/photos/31937555/pexels-photo-31937555/free-photo-of-ch-ng-sach-co-danh-d-u-trang-tren-ban.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2')">
            <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2">
                <h2 class="text-white text-[48px] md:text-[70px] font-semibold font-[Poppins] text-center">Tham gia Beebook</h2>
            </div>
        </div>

        <div class="max-w-screen-xl mx-auto px-4 py-12">
            <div class="text-left mb-16">
                <h2 class="text-[32px] md:text-[48px] font-semibold font-[Poppins] text-gray-900 max-w-3xl mb-6 leading-tight">
                    Mang điều bất ngờ đến từng trang sách kể từ 2020.
                </h2>
                <p class="text-lg leading-relaxed text-gray-700 font-[Poppins] max-w-2xl">
                    Trong suốt những năm qua, Beebook – một nhóm nhỏ những người yêu sách tại Cần Thơ – luôn tự hào mang đến cho bạn những điều mới mẻ trong thế giới đọc. Mỗi cuốn sách được chúng tôi chọn lựa không chỉ vì nội dung, mà còn vì khả năng truyền cảm hứng, thay đổi góc nhìn và chạm đến chiều sâu tâm hồn. Từ không gian yên tĩnh giữa lòng Cần Thơ, chúng tôi xây dựng Beebook như một nơi bạn không chỉ mua sách – mà tìm thấy chính mình trong từng trang giấy.
                </p>

                <blockquote class="mt-8 font-[Playfair_Display] italic text-xl text-gray-800">
                    <p>“Thời gian có giá trị hơn tiền bạc. Bạn có thể có thêm tiền, nhưng bạn không thể có thêm thời gian.”</p>
                    <cite class="block mt-4 text-base text-gray-500">– Jim Rohn</cite>
                </blockquote>
            </div>

            <div class="flex flex-wrap justify-center gap-6">
                @php
                    $cards = [
                        ['caption' => 'hướng đến người đọc', 'img' => 'https://images.pexels.com/photos/5084674/pexels-photo-5084674.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2'],
                        ['caption' => 'Tuyển chọn nội dung', 'img' => 'https://images.pexels.com/photos/3007370/pexels-photo-3007370.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2'],
                        ['caption' => 'lan tỏa khắp Việt Nam', 'img' => 'https://images.pexels.com/photos/7412073/pexels-photo-7412073.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2'],
                    ];
                @endphp
                @foreach ($cards as $card)
                    <div class="relative rounded-2xl overflow-hidden shadow-lg transition-transform hover:-translate-y-1 w-full max-w-[380px] h-[450px]">
                        <img src="{{ $card['img'] }}" alt="" class="w-full h-full object-cover" />
                        <div class="absolute bottom-4 w-full text-center text-white text-2xl font-[Playfair_Display] text-shadow-md px-4">
                            {{ $card['caption'] }}
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-20">
                <h2 class="text-3xl md:text-4xl font-bold mb-10 text-center">Nhà sáng lập</h2>
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-6 justify-center">
                    @php
                        $founders = [
                            ['name' => 'Lý Hoàng Mến', 'img' => 'https://example.com/diver1.jpg'],
                            ['name' => 'Nguyễn Hoài Bão', 'img' => 'https://example.com/diver2.jpg'],
                            ['name' => 'Đặng Nhựt Tiến', 'img' => 'https://example.com/diver3.jpg'],
                            ['name' => 'Nguyễn Trung Sang', 'img' => 'https://example.com/diver4.jpg'],
                            ['name' => 'Châu Gia Bảo', 'img' => 'https://example.com/diver5.jpg'],
                            ['name' => 'Dương Chí Hào', 'img' => 'https://example.com/diver6.jpg'],
                        ];
                    @endphp
                    @foreach ($founders as $f)
                        <div class="text-center">
                            <img src="{{ $f['img'] }}" alt="{{ $f['name'] }}" class="rounded-full w-28 h-28 mx-auto object-cover mb-2 border-4 border-white shadow" />
                            <div class="text-sm font-medium text-gray-800">{{ $f['name'] }}</div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>
</x-layouts.layout>
