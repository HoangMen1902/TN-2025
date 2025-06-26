<div>
    <div class="my-8 max-w-[1200px] m-auto ">
    <h1 class="my-6 font-semibold text-center text-sm sm:text-base md:text-lg lg:text-2xl">
        Tìm kiếm sách bằng hình ảnh
    </h1>
    <div class="flex">
        <form action="/image-search" method="POST" enctype="multipart/form-data" class="w-1/2 bg-white rounded-lg p-3 flex flex-col justify-between">
            @csrf
            <div class="relative">
                <input type="file" id="dropzone-file" class="filepond w-full" name="imageSearch" />
            </div>
            <div class="text-center">
                <button class="px-6 py-2 cursor-pointer bg-primary text-white font-semibold rounded">Tìm kiếm</button>

            </div>
        </form>
        <div class="grid gap-1 w-1/2">
            <div>
                <img class="h-auto max-w-full rounded-lg"
                    src="{{asset('/assets/images/tuoi-tre-dang-gia-bao-nhieu-1.png')}}" alt="">
            </div>
            <div class="grid grid-cols-5 gap-1">
                <div>
                    <img class="h-full object-cover max-w-full rounded-lg"
                        src="{{asset('/assets/images/dacnhantam.jpg')}}" alt="">
                </div>
                <div>
                    <img class="h-full max-w-full rounded-lg object-cover max-h-[90px] w-full"
                        src="{{asset('/assets/images/bienmoithu.jpeg')}}" alt="">
                </div>
                <div>
                    <img class="h-full max-w-full rounded-lg object-cover max-h-[90px] w-full"
                        src="{{asset('/assets/images/trenduongbang.jpeg')}}" alt="">
                </div>
                <div>
                    <img class="h-full max-w-full rounded-lg object-cover max-h-[90px] w-full"
                        src="{{asset('/assets/images/nha-gia-kim-1.jpg')}}" alt="">
                </div>
                <div>
                    <img class="h-full max-w-full rounded-lg object-cover max-h-[90px] w-full"
                        src="{{asset('/assets/images/kheoannoi.jpg')}}" alt="">
                </div>

            </div>
        </div>
    </div>


</div>
</div>
