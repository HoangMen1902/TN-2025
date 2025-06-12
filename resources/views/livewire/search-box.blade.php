<div class="w-full max-w-full md:max-w-l mx-2 relative">
    <div class="w-full max-w-full md:max-w-l mx-2 relative">
        <form wire:submit.prevent="search" class="relative w-full">
            <input
                id="searchInput"
                type="text"
                wire:model="query"
                placeholder="Sách giải hỗ trợ học tập"
                class="w-full py-1 px-2 pr-10 border border-gray-300 rounded-lg text-xs md:text-base md:py-2 md:px-3 md:pr-12">

            <button type="submit"
                class="absolute right-1 top-1/2 -translate-y-1/2 bg-blue-600 text-white px-2 py-1 text-xs md:text-sm md:px-4 md:py-1 rounded hover:bg-blue-700 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg"
                    class="w-4 h-4 md:w-6 md:h-6" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M21 21l-4.35-4.35M10.5 18a7.5 7.5 0 100-15 7.5 7.5 0 000 15z" />
                </svg>
            </button>
        </form>
    </div>



    {{-- Lịch sử --}}
   <div id="suggestionBox"
    class="hidden absolute left-0 bg-white border border-gray-200 w-full mt-1 rounded shadow z-50 
           text-sm md:text-base max-h-60 overflow-y-auto">

        @if (!empty($query) && $suggestions)
        @foreach ($suggestions as $suggestion)
        <div wire:click="search('{{ $suggestion }}')" class="px-4 py-2 hover:bg-gray-100 cursor-pointer">
            🔍 {{ $suggestion }}
        </div>
        @endforeach
        @foreach ($productSuggestions as $product)
        <div wire:click="search('{{ $product }}')" class="px-4 py-2 hover:bg-gray-100 cursor-pointer">
            📚 {{ $product }}
        </div>
        @endforeach
        @elseif (!$query && $histories)
        @foreach ($histories as $history)
        <div wire:click="search('{{ $history }}')" class="px-4 py-2 hover:bg-gray-100 cursor-pointer">
            🕘 {{ $history }}
        </div>
        @endforeach
        @endif
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const input = document.getElementById('searchInput');
            const box = document.getElementById('suggestionBox');

            input.addEventListener('focus', () => {
                box?.classList.remove('hidden');
            });

            document.addEventListener('click', (e) => {
                if (!input.contains(e.target) && !box.contains(e.target)) {
                    box.classList.add('hidden');
                }
            });
        });
    </script>
</div>