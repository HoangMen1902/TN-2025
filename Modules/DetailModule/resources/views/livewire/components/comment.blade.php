<div class="comment-section w-full p-4 mt-3">
    <h1 class="text-lg font-bold">Đánh giá sản phẩm</h1>
    <div class="mt-[15px] flex sm:flex-col lg:flex-row items-center w-full comment-wrapper">
        <div class="lg:w-[65%] sm:w-50">
            <div class="flex gap-8 ">
                <div>
                    <h1 class="text-5xl">{{ $averageRating }}/<span class="text-2xl">5</span></h1>
                    <div class="flex items-center w-fit">
                        @for ($i = 1; $i <= 5; $i++)
                            <svg xmlns="http://www.w3.org/2000/svg"
                                fill="{{ $i <= floor($averageRating) ? '#fca404' : '#ccc' }}" viewBox="0 0 24 24"
                                stroke-width="0" class="w-[18px]">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M11.48 3.499a.562.562 0 0 1 1.04 0l2.125 5.111a.563.563 0 0 0 .475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 0 0-.182.557l1.285 5.385a.562.562 0 0 1-.84.61l-4.725-2.885a.562.562 0 0 0-.586 0L6.982 20.54a.562.562 0 0 1-.84-.61l1.285-5.386a.562.562 0 0 0-.182-.557l-4.204-3.602a.562.562 0 0 1 .321-.988l5.518-.442a.563.563 0 0 0 .475-.345L11.48 3.5Z" />
                            </svg>
                        @endfor
                    </div>
                    <span class="text-sm" style="color: #777777">({{ $totalReviews }} đánh giá)</span>
                </div>

                <div class="progress-bar-wrapper w-100">
                    @for ($i = 5; $i >= 1; $i--)
                        <div class="flex items-center gap-2 mb-1">
                            <span class="w-[50px] text-sm font-extralight">{{ $i }} sao</span>
                            <div class="flex-1 bg-gray-200 rounded h-2">
                                <div class="bg-yellow-400 h-2 rounded" style="width: {{ $ratingsPercentage[$i] ?? 0 }}%">
                                </div>
                            </div>
                            <div class="text-sm text-black w-[40px] text-right">{{ $ratingsPercentage[$i] ?? 0 }}%</div>
                        </div>
                    @endfor
                </div>
            </div>
        </div>

        <!-- Nút mở modal -->
        <!-- Nút mở modal -->
        <button wire:click="writeReview "
            class="comment-btn-wrapper cursor-pointer flex items-center justify-center border-blue-500 gap-2 text-blue-600 font-bold text-lg hover:opacity-80 ">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="#0A68FF"
                class="w-[19px]">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L6.832 19.82a4.5 4.5 0 0 1-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 0 1 1.13-1.897L16.863 4.487Zm0 0L19.5 7.125" />
            </svg>
            Viết đánh giá
        </button>


        <!-- Modal -->
        @if ($showModal)
            <div class="fixed inset-0 bg-white/200 bg-opacity-30 flex items-center justify-center z-50"
                aria-labelledby="modal-title" role="dialog" aria-modal="true">
                <div class="bg-white rounded-xl shadow-xl max-w-md w-full mx-4 p-6  relative">
                    <button wire:click="$set('showModal', false)"
                        class="absolute top-4 right-4 text-gray-400 hover:text-gray-700 transition" aria-label="Đóng modal">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>

                    <h2 id="modal-title" class="text-2xl font-semibold  text-center mb-6">
                        Viết đánh giá
                    </h2>

                    <form wire:submit.prevent="submitReview" class="space-y-5">
                        <div class="flex justify-center space-x-2 text-4xl select-none">
                            @for ($i = 1; $i <= 5; $i++)
                                <button type="button" wire:click="$set('rating', {{ $i }})"
                                    class="focus:outline-none transition-colors duration-200 cursor-pointer"
                                    aria-label="Đánh giá {{ $i }} sao">
                                    <span class="{{ $rating >= $i ? 'text-yellow-400' : 'text-gray-300' }}">
                                        ★
                                    </span>
                                </button>
                            @endfor
                        </div>
                        @error('rating')
                            <p class="text-red-600 text-sm text-center">{{ $message }}</p>
                        @enderror

                        <textarea wire:model.defer="review"
                            class="w-full px-4 py-3 border cursor-pointer border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-400 focus:border-blue-400 resize-none"
                            rows="5" placeholder="Nhập nội dung đánh giá..."></textarea>
                        @error('review')
                            <p class="text-red-600 text-sm">{{ $message }}</p>
                        @enderror

                        <button type="submit"
                            class="w-full bg-blue-600 hover:bg-blue-700 cursor-pointer text-white font-semibold py-3 rounded-lg transition">
                            Gửi đánh giá
                        </button>
                    </form>
                </div>
            </div>
        @endif





    </div>
    <div class="comment-choice border-b border-neutral-600 flex gap-6 text-sm mt-3">
        <a href="javascript:void(0)" wire:click="$set('selectedFavorite', false)" @class(['py-[10px]', 'active' => !$selectedFavorite])>
            Mới nhất
        </a>

        <a href="javascript:void(0)" wire:click="$set('selectedFavorite', true)" @class(['py-[10px]', 'active' => $selectedFavorite])>
            Yêu thích nhất
        </a>
    </div>

    <div class="">
        @if (!$selectedFavorite)
            @foreach ($ratingsNewest as $rating)
                <div class="mt-[10px] comment-item mb-6">
                    <div class="flex">
                        <div class="flex flex-col w-2/12">
                            <div class="author-name">
                                {{-- Ẩn 3 ký tự cuối tên user --}}
                                <span class="text-sm">
                                    {{ \Str::limit($rating->user->name, strlen($rating->user->name) - 3, '') . '***' }}
                                </span>
                            </div>
                            <div class="day-commented text-sm text-neutral-500">
                                <span>
                                    {{ $rating->created_at ? $rating->created_at->diffForHumans() : 'Không có ngày' }}
                                </span>

                            </div>
                        </div>
                        <div>
                            <div class="flex">
                                {{-- Hiển thị sao vàng theo số điểm rating --}}
                                @for ($i = 1; $i <= 5; $i++)
                                    @if ($i <= $rating->rating)
                                        {{-- Sao vàng --}}
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="#fca404" viewBox="0 0 24 24" stroke-width="0"
                                            stroke="none" class="size-6" style="width:18px; height:18px;">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M11.48 3.499a.562.562 0 0 1 1.04 0l2.125 5.111a.563.563 0 0 0 .475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 0 0-.182.557l1.285 5.385a.562.562 0 0 1-.84.61l-4.725-2.885a.562.562 0 0 0-.586 0L6.982 20.54a.562.562 0 0 1-.84-.61l1.285-5.386a.562.562 0 0 0-.182-.557l-4.204-3.602a.562.562 0 0 1 .321-.988l5.518-.442a.563.563 0 0 0 .475-.345z" />
                                        </svg>
                                    @else
                                        {{-- Sao trắng có viền xám --}}
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                            stroke="#ccc" class="size-6" style="width:18px; height:18px;">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M11.48 3.499a.562.562 0 0 1 1.04 0l2.125 5.111a.563.563 0 0 0 .475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 0 0-.182.557l1.285 5.385a.562.562 0 0 1-.84.61l-4.725-2.885a.562.562 0 0 0-.586 0L6.982 20.54a.562.562 0 0 1-.84-.61l1.285-5.386a.562.562 0 0 0-.182-.557l-4.204-3.602a.562.562 0 0 1 .321-.988l5.518-.442a.563.563 0 0 0 .475-.345z" />
                                        </svg>
                                    @endif
                                @endfor

                            </div>
                            <p class="text-[15px] mt-2">
                                {{ $rating->review }}
                            </p>

                            <div class="flex items-center gap-4 mt-4">
                                <a href="javascript:void(0)" class="flex items-center text-sm text-neutral-500"
                                    wire:click="toggleLike({{ $rating->id }})">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                        class="size-6 {{ in_array($rating->id, $likedRatings) ? 'text-red-400 fill-red-400' : 'text-gray-400 fill-none' }}"
                                        stroke-width="1.5" stroke="currentColor" fill="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78L12 21.23l8.84-8.84a5.5 5.5 0 0 0 0-7.78z" />
                                    </svg>

                                    <span class="ml-1">
                                        {{ in_array($rating->id, $likedRatings) ? 'Đã thích' : 'Thích' }}
                                        ({{ $rating->likedUsers->count() }}) {{-- Đếm số lượt thích --}}
                                    </span>
                                </a>

                                <a href="javascript:void(0)" class="flex items-center text-sm text-neutral-500">
                                    {{-- Icon Báo cáo --}}
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1"
                                        stroke="currentColor" class="size-6">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M3 3v1.5M3 21v-6m0 0 2.77-.693a9 9 0 0 1 6.208.682l.108.054a9 9 0 0 0 6.086.71l3.114-.732a48.524 48.524 0 0 1-.005-10.499l-3.11.732a9 9 0 0 1-6.085-.711l-.108-.054a9 9 0 0 0-6.208-.682L3 4.5M3 15V4.5" />
                                    </svg>

                                    Báo cáo
                                </a>
                            </div>

                        </div>
                    </div>
                </div>
            @endforeach
        @endif
        @if ($selectedFavorite)
            @foreach ($ratingsFavorite as $rating)
                <div class="mt-[10px] comment-item mb-6">
                    <div class="flex">
                        <div class="flex flex-col w-2/12">
                            <div class="author-name">
                                {{-- Ẩn 3 ký tự cuối tên user --}}
                                <span class="text-sm">
                                    {{ \Str::limit($rating->user->name, strlen($rating->user->name) - 3, '') . '***' }}
                                </span>
                            </div>
                            <div class="day-commented text-sm text-neutral-500">
                                <span>
                                    {{ $rating->created_at ? $rating->created_at->diffForHumans() : 'Không có ngày' }}
                                </span>

                            </div>
                        </div>
                        <div>
                            <div class="flex">
                                {{-- Hiển thị sao vàng theo số điểm rating --}}
                                @for ($i = 1; $i <= 5; $i++)
                                    @if ($i <= $rating->rating)
                                        {{-- Sao vàng --}}
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="#fca404" viewBox="0 0 24 24" stroke-width="0"
                                            stroke="none" class="size-6" style="width:18px; height:18px;">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M11.48 3.499a.562.562 0 0 1 1.04 0l2.125 5.111a.563.563 0 0 0 .475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 0 0-.182.557l1.285 5.385a.562.562 0 0 1-.84.61l-4.725-2.885a.562.562 0 0 0-.586 0L6.982 20.54a.562.562 0 0 1-.84-.61l1.285-5.386a.562.562 0 0 0-.182-.557l-4.204-3.602a.562.562 0 0 1 .321-.988l5.518-.442a.563.563 0 0 0 .475-.345z" />
                                        </svg>
                                    @else
                                        {{-- Sao trắng có viền xám --}}
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                            stroke="#ccc" class="size-6" style="width:18px; height:18px;">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M11.48 3.499a.562.562 0 0 1 1.04 0l2.125 5.111a.563.563 0 0 0 .475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 0 0-.182.557l1.285 5.385a.562.562 0 0 1-.84.61l-4.725-2.885a.562.562 0 0 0-.586 0L6.982 20.54a.562.562 0 0 1-.84-.61l1.285-5.386a.562.562 0 0 0-.182-.557l-4.204-3.602a.562.562 0 0 1 .321-.988l5.518-.442a.563.563 0 0 0 .475-.345z" />
                                        </svg>
                                    @endif
                                @endfor

                            </div>
                            <p class="text-[15px] mt-2">
                                {{ $rating->review }}
                            </p>

                            <div class="flex items-center gap-4 mt-4">
                                <div wire:key="rating-favorite-{{ $rating->id }}">
                                    <a href="javascript:void(0)" class="flex items-center text-sm text-neutral-500"
                                        wire:click="toggleLike({{ $rating->id }})">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                            class="size-6 {{ in_array($rating->id, $likedRatings) ? 'text-red-400 fill-red-400' : 'text-gray-400 fill-none' }}"
                                            stroke-width="1.5" stroke="currentColor" fill="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78L12 21.23l8.84-8.84a5.5 5.5 0 0 0 0-7.78z" />
                                        </svg>
                                        
                                        <span class="ml-1">
                                            {{ in_array($rating->id, $likedRatings) ? 'Đã thích' : 'Thích' }}
                                            ({{ $rating->likedUsers->count() }})
                                        </span>
                                    </a>
                                </div>

                                <a href="javascript:void(0)" class="flex items-center text-sm text-neutral-500">
                                    {{-- Icon Báo cáo --}}
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1"
                                        stroke="currentColor" class="size-6">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M3 3v1.5M3 21v-6m0 0 2.77-.693a9 9 0 0 1 6.208.682l.108.054a9 9 0 0 0 6.086.71l3.114-.732a48.524 48.524 0 0 1-.005-10.499l-3.11.732a9 9 0 0 1-6.085-.711l-.108-.054a9 9 0 0 0-6.208-.682L3 4.5M3 15V4.5" />
                                    </svg>

                                    Báo cáo
                                </a>
                            </div>

                        </div>
                    </div>
                </div>
            @endforeach
        @endif
    </div>
</div>