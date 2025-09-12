<div>
    <a href="#" class="flex items-center">

        <div class="rating-box flex  ">
            <a href="#" class="flex items-center">
                @for ($i = 1; $i <= 5; $i++)
                    <svg xmlns="http://www.w3.org/2000/svg" fill="{{ $i <= $avgRating ? '#f59e42' : 'none' }}"
                        viewBox="0 0 24 24" stroke-width="0.5" stroke="currentColor" class="size-4">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M11.48 3.499a.562.562 0 0 1 1.04 0l2.125 5.111a.563.563 0 0 0 .475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 0 0-.182.557l1.285 5.385a.562.562 0 0 1-.84.61l-4.725-2.885a.562.562 0 0 0-.586 0L6.982 20.54a.562.562 0 0 1-.84-.61l1.285-5.386a.562.562 0 0 0-.182-.557l-4.204-3.602a.562.562 0 0 1 .321-.988l5.518-.442a.563.563 0 0 0 .475-.345L11.48 3.5Z" />
                    </svg>
                @endfor
                <span class="text-amber-400 text-sm ml-[4px]">({{ $count }} đánh giá)</span>
            </a>
        </div>
    </a>
  
</div>