<div class="my-8 max-w-[1200px] m-auto ">
    <h1 class="my-6 font-semibold text-center text-sm sm:text-base md:text-lg lg:text-2xl">
        Tìm kiếm sách bằng hình ảnh
    </h1>
    <div class="flex">
        <form class="w-1/2 relative">
            <div class="flex items-center justify-center h-full">
                <label for="dropzone-file"
                    class="h-full flex flex-col items-center justify-center w-full border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50  hover:bg-gray-100 ">
                    <div class="flex flex-col items-center justify-center pt-5 pb-6">
                        <svg class="w-8 h-8 mb-4 text-gray-500 " aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                            fill="none" viewBox="0 0 20 16">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2" />
                        </svg>
                        <p class="mb-2 text-sm text-gray-500 "><span class="font-semibold">Ấn để tải lên</span> hoặc kéo
                            thả vào đây</p>
                        <p class="text-xs text-gray-500 ">PNG hoặc JPG (Tối đa 20MB)</p>
                    </div>
                    <input id="dropzone-file" type="file" class="hidden" />
                </label>
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