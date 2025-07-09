<div>
    @if ($preordered)
        <button wire:click="cancel"
            class="w-full bg-red-600 text-white font-bold py-2 flex items-center justify-center hover:bg-red-700 rounded">
            <svg xmlns="http://www.w3.org/2000/svg" class="size-5 mr-2" fill="none" viewBox="0 0 24 24"
                stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
            </svg>
            Huỷ đặt trước
        </button>
    @else
        <button wire:click="preorder"
            class="w-full bg-primary text-white font-bold py-2 flex items-center justify-center hover:bg-primary-dark rounded">
            <svg xmlns="http://www.w3.org/2000/svg" class="size-5 mr-2" fill="none" viewBox="0 0 24 24"
                stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437
                       M7.5 14.25a3 3 0 0 0-3 3h15.75
                       m-12.75-3h11.218c1.121-2.3 2.1-4.684 
                       2.924-7.138a60.114 60.114 0 0 0-16.536-1.84
                       M7.5 14.25 5.106 5.272
                       M6 20.25a.75.75 0 1 1-1.5 0 
                       .75.75 0 0 1 1.5 0Zm12.75 0
                       a.75.75 0 1 1-1.5 0 
                       .75.75 0 0 1 1.5 0Z" />
            </svg>
            Đặt trước sản phẩm
        </button>
    @endif
</div>
