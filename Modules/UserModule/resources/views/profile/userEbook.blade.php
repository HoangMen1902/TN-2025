@php
    $tab = request()->get('tab', 'purchased');
@endphp

<x-layouts.layout>
    <div class="container mx-auto max-w-[1200px] my-3 md:my-6 flex flex-col px-4 md:px-0">
        <div class="flex flex-col md:flex-row md:h-full w-full">
            {{-- Sidebar --}}
            <div class="w-full h-[500px] md:w-[300px] md:h-[500px] mb-4 md:mb-0">
                <x-usermodule::sidebar />
            </div>

            {{-- Main content --}}
            <div class="bg-white w-full md:w-[900px] py-4 md:py-8 rounded shadow-sm">
                <div class="mb-4 md:mb-6 flex justify-between items-center px-4">
                    <h1 class="text-lg md:text-2xl font-medium">Ebook</h1>
                </div>
                <hr class="border-t border-gray-300 my-2 md:my-4 mx-4">

                {{-- Tabs --}}
                <div class="mb-4 border-b border-gray-200">
                    <ul class="flex flex-wrap text-sm font-medium text-center">
                        <li class="mr-2">
                            <a href="?tab=purchased"
                                class="inline-block p-4 border-b-2 {{ $tab === 'purchased' ? 'border-red-500 text-red-500' : 'border-transparent text-gray-500 hover:text-gray-600 hover:border-gray-300' }}">
                                Ebook đã mua
                            </a>
                        </li>
                        <li class="mr-2">
                            <a href="?tab=favorite"
                                class="inline-block p-4 border-b-2 {{ $tab === 'favorite' ? 'border-red-500 text-red-500' : 'border-transparent text-gray-500 hover:text-gray-600 hover:border-gray-300' }}">
                                Yêu thích
                            </a>
                        </li>
                    </ul>
                </div>

                {{-- Tab content --}}
                <div class="px-4">
                    @if ($tab === 'purchased')

                        @forelse($purchasedEbooks as $ebook)
                                                @php
                                                    $thumbnailPath = $ebook->cover_image ?? null;
                                                @endphp
                            <a href="{{ route('ebooks.show', $ebook->id) ?? url('/chi-tiet/' . $ebook->slug) }}">
                                                    <div class="">
                                                        <div class="bg-white border border-gray-200 rounded-lg p-4 shadow-sm mb-4 w-full">
                                                            <div class="flex items-center mb-3">
                                                                <h2 class="text-sm font-bold  text-gray-800 uppercase">
                                                                    {{ $ebook->title }}
                                                                </h2>
                                                            </div>
                                                            <hr class="border-gray-200 mb-3">
                                                            <div class="flex items-start space-x-4 w-full">
                                                                <div class="flex-shrink-0">
                                                                    <img class="h-16 w-16 object-contain"
                                                                        src="{{ asset('storage/' . $thumbnailPath) }}" alt="{{ $ebook->name }}">
                                                                </div>
                                                                <div class="flex-1 min-w-0">
                                                                    <p class="text-sm text-red-500 font-medium  md:text-base mb-1">
                                                                        {{$ebook->price}}<span class="text-sm">đ</span>
                                                                    </p>
                                                                    <p class="text-sm font-medium text-gray-800 mb-1 line-clamp-2 ">
                                                                        {{ $ebook->description }}
                                                                    </p>
                                                                    <p class="text-xs text-gray-500">đã thanh toán {{ $ebook->created_at }}</p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </a>
                        @empty
                            <h3>Bạn chưa mua ebook nào.</h3>
                        @endforelse






                    @elseif ($tab === 'favorite')
                        @forelse($likedEbooks as $ebook)
                            @php
                                $thumbnailPath = $ebook->cover_image ?? null;
                            @endphp
                            <a href="{{ route('ebooks.show', $ebook->id) ?? url('/chi-tiet/' . $ebook->slug) }}">

                            <div class="">
                                
                                <div class="bg-white border border-gray-200 rounded-lg p-4 shadow-sm mb-4 w-full">
                                    <div class="flex items-center mb-3">
                                        <h2 class="text-sm font-bold text-gray-800 uppercase">
                                            {{ $ebook->title }}
                                        </h2>
                                    </div>
                                    <hr class="border-gray-200 mb-3">
                                    <div class="flex items-start space-x-4 w-full">
                                        <div class="flex-shrink-0">
                                            <img class="h-16 w-16 object-contain" src="{{ asset('storage/' . $thumbnailPath) }}"
                                                alt="{{ $ebook->name }}">
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm text-red-500 font-medium md:text-base mb-1">
                                                {{ $ebook->price }}<span class="text-sm">đ</span>
                                            </p>
                                            <p class="text-sm font-medium text-gray-800 mb-1 line-clamp-2">
                                                {{ $ebook->description }}
                                            </p>
                                            <p class="text-xs text-gray-500">{{ $ebook->created_at }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            </a>
                        @empty
                            <h3>Bạn chưa đánh dấu yêu thích ebook nào.</h3>
                        @endforelse
                    @endif

                </div>
            </div>
        </div>
    </div>
</x-layouts.layout>