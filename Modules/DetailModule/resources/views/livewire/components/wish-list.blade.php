<div>
@auth
    <button
        wire:click="toggleWishlist"
        class="cursor-pointer flex items-center justify-center w-9 h-9 rounded-full border border-gray-300 hover:border-red-500 transition-all duration-300 group"
        title="{{ $isInWishlist ? 'Xóa khỏi danh sách yêu thích' : 'Thêm vào danh sách yêu thích' }}"
    >
        <svg xmlns="http://www.w3.org/2000/svg"
             viewBox="0 0 24 24"
             stroke-width="1.5"
             stroke="currentColor"
             class="w-5 h-5 transition-all duration-200 ease-in-out group-hover:scale-110 text-red-500"
             fill="{{ $isInWishlist ? '#ef4444' : 'none' }}">
            <path stroke-linecap="round" stroke-linejoin="round"
                  d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z" />
        </svg>
    </button>
@endauth

    @guest
        <a href="{{ route('login') }}"
           class="cursor-pointer flex items-center justify-center w-9 h-9 rounded-full border border-gray-300 hover:border-red-500 transition-all duration-300 group"
           title="Đăng nhập để thêm vào yêu thích">
            <svg xmlns="http://www.w3.org/2000/svg"
                 viewBox="0 0 24 24"
                 stroke-width="1.5"
                 stroke="currentColor"
                 class="w-5 h-5 text-gray-400 group-hover:text-red-500 transition-all duration-200 ease-in-out">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z" />
            </svg>
        </a>
    @endguest
</div>
