<div class="w-full px-2 md:px-4 relative max-w-[700px] mx-auto">
    <form wire:submit.prevent="search" class="relative w-full">
        <input
            id="searchInput"
            type="text"
            wire:model="query"
            placeholder="Tìm kiếm sản phẩm"
            class="w-full py-2 pl-3 pr-24 md:pr-28 text-sm md:text-base border border-gray-300 rounded-lg">

        <div class="absolute top-1/2 -translate-y-1/2 right-2 flex items-center space-x-2 md:space-x-3">
            <button type="button" id="voiceSearch"
                class="text-gray-500 hover:text-blue-600 text-lg cursor-pointer md:text-xl">
                <svg xmlns="http://www.w3.org/2000/svg"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke-width="1.5"
                    stroke="currentColor"
                    width="20"
                    height="20">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 18.75a6 6 0 0 0 6-6v-1.5m-6 7.5a6 6 0 0 1-6-6v-1.5m6 7.5v3.75m-3.75 0h7.5M12 15.75a3 3 0 0 1-3-3V4.5a3 3 0 1 1 6 0v8.25a3 3 0 0 1-3 3Z" />
                </svg>
            </button>
            <a href="{{ route('imageSearch') }}" class="text-gray-500 hover:text-blue-600 text-lg cursor-pointer md:text-xl">
                <svg xmlns="http://www.w3.org/2000/svg"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke-width="1.5"
                    stroke="currentColor"
                    width="22"
                    height="22">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.827 6.175A2.31 2.31 0 0 1 5.186 7.23c-.38.054-.757.112-1.134.175C2.999 7.58 2.25 8.507 2.25 9.574V18a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18V9.574c0-1.067-.75-1.994-1.802-2.169a47.865 47.865 0 0 0-1.134-.175 2.31 2.31 0 0 1-1.64-1.055l-.822-1.316a2.192 2.192 0 0 0-1.736-1.039 48.774 48.774 0 0 0-5.232 0 2.192 2.192 0 0 0-1.736 1.039l-.821 1.316Z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 12.75a4.5 4.5 0 1 1-9 0 4.5 4.5 0 0 1 9 0ZM18.75 10.5h.008v.008h-.008V10.5Z" />
                </svg>
            </a>

            <button type="submit"
                class="hidden md:inline-flex bg-blue-600 text-white px-3 py-1 cursor-pointer md:px-4 md:py-1.5 rounded hover:bg-blue-700 transition-all text-sm md:text-base">
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
        document.addEventListener('DOMContentLoaded', function() {
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

                recognition.onresult = function(event) {
                    const transcript = event.results[0][0].transcript;
                    input.value = transcript;
                    input.dispatchEvent(new Event('input'));
                    voiceBtn.innerText = '🎙️';
                    setTimeout(() => {
                        document.querySelector('form').dispatchEvent(new Event('submit', {
                            bubbles: true
                        }));
                    }, 300);
                };

                recognition.onerror = function() {
                    voiceBtn.innerText = '🎙️';
                };

                recognition.onend = function() {
                    voiceBtn.innerText = '🎙️';
                };
            }
        });
    </script>
</div>