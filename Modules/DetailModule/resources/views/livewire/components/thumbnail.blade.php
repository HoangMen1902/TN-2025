<div class="thumbnail-holder flex flex-col justify-items-center items-center">
    <div class="thumbnail hidden lg:block">
        <img src="{{ asset('storage/' . $thumbnail) }}" alt="" class="thumbnail-img">
    </div>
    <div class="thumbnail  gallery block lg:hidden thumbnail-responsive">

        <a href="{{ asset('storage/products/image_226331.jpg') }}" data-pswp-width="200" data-pswp-height="200"
            target="_blank" class="gallery-item-holder">
            <img src="{{ asset('storage/products/image_226331.jpg') }}" alt="" class="gallery-item box-border">
        </a>
        <a href="{{ asset('storage/products/image_226331.jpg') }}" data-pswp-width="200" data-pswp-height="200"
            target="_blank" class="gallery-item-holder hidden ">
            <img src="{{ asset('storage/products/image_226331.jpg') }}" alt="" class="gallery-item box-border">
        </a>
        <a href="{{ asset('storage/products/image_226331.jpg') }}" data-pswp-width="200" data-pswp-height="200"
            target="_blank" class="gallery-item-holder hidden ">
            <img src="{{ asset('storage/products/image_226331.jpg') }}" alt="" class="gallery-item box-border">
        </a>
        <a href="{{ asset('storage/products/image_226331.jpg') }}" data-pswp-width="200" data-pswp-height="200"
            target="_blank" class="gallery-item-holder hidden ">
            <img src="{{ asset('storage/products/image_226331.jpg') }}" alt="" class="gallery-item box-border">
        </a>

        <a href="{{ asset('storage/products/image_226331.jpg') }}" data-pswp-width="200" data-pswp-height="200"
            target="_blank" class="gallery-item-holder hidden">
            <img src="{{ asset('storage/products/image_226331.jpg') }}" alt="" class="gallery-item box-border">
        </a>
        <a href="{{ asset('storage/products/image_226331.jpg') }}" data-pswp-width="200" data-pswp-height="200"
            target="_blank" class="gallery-item-holder hidden">
            <img src="{{ asset('storage/products/image_226331.jpg') }}" alt="" class="gallery-item box-border">
        </a>
        <a href="{{ asset('storage/products/image_226331.jpg') }}" data-pswp-width="200" data-pswp-height="200"
            target="_blank" class="gallery-item-holder hidden">
            <img src="{{ asset('storage/products/image_226331.jpg') }}" alt="" class="gallery-item box-border">
        </a>
    </div>
    <div class="gallery hidden lg:flex w-full mt-4">
@if ($type === "product")
    @php
        $count = 1;
        $fifthImg;
    @endphp
    @foreach ($currentSku->images as $index => $image)

        @if($index >= 5)
            @php
                $count++
            @endphp
            @if ($index === 5)
                @php
                    $fifthImg = $image;
                @endphp
            @endif

            <a href="{{ asset('storage/' . $image) }}" data-pswp-width="450" data-pswp-height="450" target="_blank"
                class="gallery-item-holder w-[84px] h-[84px] hidden">
                <img src="{{ asset('storage/' . $image) }}" alt=""
                    class="gallery-item box-border w-full h-full object-contain">
            </a>
        @else
            <a href="{{ asset('storage/' . $image) }}" target="_blank" data-pswp-width="450" data-pswp-height="450"
                class="gallery-item-holder w-[84px] h-[84px]">
                <img src="{{ asset('storage/' . $image) }}" alt=""
                    class="gallery-item box-border w-full h-full object-cover">
            </a>
        @endif
    @endforeach
    @if (isset($fifthImg))
        <div class="last-image relative z-0">
            <a href="{{ asset('storage/' . $fifthImg) }}" data-pswp-width="450" data-pswp-height="450" target="_blank"
                class="gallery-item-holder w-[84px] h-[84px]">
                <img src="{{ asset('storage/' . $fifthImg) }}" alt=""
                    class="gallery-item box-border blur-[1px] w-full h-full object-contain">
            </a>
            <span
                class="absolute inset-0 flex items-center justify-center font-bold text-xl text-white bg-black/40 z-10 pointer-events-none image-count">
                +{{$count}}
            </span>
        </div>
    @endif
@elseif($type === "combo")
@php
$count = 1;
$fifthImg;
@endphp
@foreach ($data->images as $index => $image)

@if($index >= 5)
    @php
        $count++
    @endphp
    @if ($index === 5)
        @php
            $fifthImg = $image;
        @endphp
    @endif

    <a href="{{ asset('storage/' . $image) }}" data-pswp-width="450" data-pswp-height="450" target="_blank"
        class="gallery-item-holder w-[84px] h-[84px] hidden">
        <img src="{{ asset('storage/' . $image) }}" alt=""
            class="gallery-item box-border w-full h-full object-contain">
    </a>
@else
    <a href="{{ asset('storage/' . $image) }}" target="_blank" data-pswp-width="450" data-pswp-height="450"
        class="gallery-item-holder w-[84px] h-[84px]">
        <img src="{{ asset('storage/' . $image) }}" alt=""
            class="gallery-item box-border w-full h-full object-cover">
    </a>
@endif
@endforeach
@if (isset($fifthImg))
<div class="last-image relative z-0">
    <a href="{{ asset('storage/' . $fifthImg) }}" data-pswp-width="450" data-pswp-height="450" target="_blank"
        class="gallery-item-holder w-[84px] h-[84px]">
        <img src="{{ asset('storage/' . $fifthImg) }}" alt=""
            class="gallery-item box-border blur-[1px] w-full h-full object-contain">
    </a>
    <span
        class="absolute inset-0 flex items-center justify-center font-bold text-xl text-white bg-black/40 z-10 pointer-events-none image-count">
        +{{$count}}
    </span>
</div>
@endif
@endif
    </div>
    <div class="action-button w-full lg:py-4 hidden lg:block">
        @if (!isset($currentSku->product->published_at) || new DateTime($currentSku->product->published_at) < new DateTime())
        <form action="{{route("cart.add")}}" method="POST"" class=" flex items-center" id="addToCart">
            @csrf
            <button class="w-1/2 cart-add-item font-bold flex items-center justify-center sm:hidden lg:flex"
                name="{{$type === "product" ? "sku_id" : "combo_id"}}" value="{{$type === "product" ? $currentSku->id : $data->id}}"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                    stroke-width="1.5" stroke="currentColor" class="size-6">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 14.25 5.106 5.272M6 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm12.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z" />
                </svg>
                Thêm vào giỏ hàng</button>
            <button class="w-1/2 item-checkout text-white font-bold sm:hidden lg:block" name="checkout" value="">Mua
                ngay</button>
        </form>
        @else
        <form  method="POST" class="w-full">
            @csrf
            <button style="width:100%; color:white" class="bg-primary cart-add-item font-bold flex items-center justify-center sm:hidden lg:flex"
                name="sku_id" value="{{$currentSku->id}}"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                    stroke-width="1.5" stroke="currentColor" class="size-6">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 14.25 5.106 5.272M6 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm12.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z" />
                </svg>
                Đặt trước sản phẩm</button>
        </form>
        @endif
                                              <livewire:detailmodule::components.product-preview-modal :data="$data">
                    </livewire:detailmodule::components.product-preview-modal>
    </div>
    <div class="hidden lg:block preferential-policy w-full">
        <span class="font-bold">Chính sách ưu đãi của BeeBook</span>
        <div class="policy-group flex flex-col w-full mt-4 pl-2">
            <a href="#" class="mb-4 w-full text-sm flex items-center"><svg xmlns="http://www.w3.org/2000/svg"
                    fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="red" class="size-5">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M8.25 18.75a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 0 1-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 0 0-3.213-9.193 2.056 2.056 0 0 0-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 0 0-10.026 0 1.106 1.106 0 0 0-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12" />
                </svg>
                <span class="font-bold mr-[5px]">Thời gian giao hàng:</span> Giao nhanh và uy tín <span
                    class="ml-auto"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                        stroke-width="1.5" stroke="currentColor" class="size-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
                    </svg>
                </span>
            </a>
            <a href="#" class="mb-4 w-full text-sm flex items-center"><svg xmlns="http://www.w3.org/2000/svg"
                    fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="red" class="size-5">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M19.5 12c0-1.232-.046-2.453-.138-3.662a4.006 4.006 0 0 0-3.7-3.7 48.678 48.678 0 0 0-7.324 0 4.006 4.006 0 0 0-3.7 3.7c-.017.22-.032.441-.046.662M19.5 12l3-3m-3 3-3-3m-12 3c0 1.232.046 2.453.138 3.662a4.006 4.006 0 0 0 3.7 3.7 48.656 48.656 0 0 0 7.324 0 4.006 4.006 0 0 0 3.7-3.7c.017-.22.032-.441.046-.662M4.5 12l3 3m-3-3-3 3" />
                </svg>
                <span class="font-bold mr-[5px]">Chính sách đổi trả: </span> Đổi trả miễn phí toàn
                quốc <span class="ml-auto"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                        stroke-width="1.5" stroke="currentColor" class="size-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
                    </svg>
                </span>
            </a>
            <a href="#" class=" w-full text-sm flex items-center"><svg xmlns="http://www.w3.org/2000/svg" fill="none"
                    viewBox="0 0 24 24" stroke-width="2" stroke="red" class="size-5">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M13.5 21v-7.5a.75.75 0 0 1 .75-.75h3a.75.75 0 0 1 .75.75V21m-4.5 0H2.36m11.14 0H18m0 0h3.64m-1.39 0V9.349M3.75 21V9.349m0 0a3.001 3.001 0 0 0 3.75-.615A2.993 2.993 0 0 0 9.75 9.75c.896 0 1.7-.393 2.25-1.016a2.993 2.993 0 0 0 2.25 1.016c.896 0 1.7-.393 2.25-1.015a3.001 3.001 0 0 0 3.75.614m-16.5 0a3.004 3.004 0 0 1-.621-4.72l1.189-1.19A1.5 1.5 0 0 1 5.378 3h13.243a1.5 1.5 0 0 1 1.06.44l1.19 1.189a3 3 0 0 1-.621 4.72M6.75 18h3.75a.75.75 0 0 0 .75-.75V13.5a.75.75 0 0 0-.75-.75H6.75a.75.75 0 0 0-.75.75v3.75c0 .414.336.75.75.75Z" />
                </svg>
                <span class="font-bold mr-[5px]">Chính sách khách sỉ:</span> Ưu đãi khi mua số lượng
                lớn<span class="ml-auto"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                        stroke-width="1.5" stroke="currentColor" class="size-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
                    </svg>
                </span>
            </a>
        </div>
    </div>
</div>