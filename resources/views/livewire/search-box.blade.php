<div class="w-full px-2 md:px-4 relative max-w-[700px] mx-auto">
    <form wire:submit.prevent="search" class="relative w-full">
        <input
            id="searchInput"
            type="text"
            wire:model="query"
            placeholder="Sách giải hỗ trợ học tập"
            class="w-full py-2 pl-3 pr-24 md:pr-28 text-sm md:text-base border border-gray-300 rounded-lg">

        <div class="absolute top-1/2 -translate-y-1/2 right-2 flex items-center space-x-2 md:space-x-3">
            <button type="button" id="voiceSearch"
                class="text-gray-500 hover:text-blue-600 text-lg md:text-xl">
                🎤
            </button>

            <button type="submit"
                class="bg-blue-600 text-white px-3 py-1 md:px-4 md:py-1.5 rounded hover:bg-blue-700 transition-all text-sm md:text-base">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 md:w-5 md:h-5" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M21 21l-4.35-4.35M10.5 18a7.5 7.5 0 100-15 7.5 7.5 0 000 15z" />
                </svg>
            </button>
        </div>
    </form>

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
        document.addEventListener('DOMContentLoaded', function () {
            const input = document.getElementById('searchInput');
            const box = document.getElementById('suggestionBox');
            const voiceBtn = document.getElementById('voiceSearch');

            input.addEventListener('focus', () => {
                box?.classList.remove('hidden');
            });

            document.addEventListener('click', (e) => {
                if (!input.contains(e.target) && !box.contains(e.target)) {
                    box.classList.add('hidden');
                }
            });

            if ('webkitSpeechRecognition' in window) {
                const recognition = new webkitSpeechRecognition();
                recognition.lang = 'vi-VN';
                recognition.continuous = false;
                recognition.interimResults = false;

                voiceBtn.addEventListener('click', () => {
                    recognition.start();
                    voiceBtn.innerText = '⏳';
                });

                recognition.onresult = function (event) {
                    const transcript = event.results[0][0].transcript;
                    input.value = transcript;
                    input.dispatchEvent(new Event('input'));
                    voiceBtn.innerText = '🎤';
                    setTimeout(() => {
                        document.querySelector('form').dispatchEvent(new Event('submit', { bubbles: true }));
                    }, 300);
                };

                recognition.onerror = function () {
                    voiceBtn.innerText = '🎤';
                };

                recognition.onend = function () {
                    voiceBtn.innerText = '🎤';
                };
            }
        });
    </script>
</div>
