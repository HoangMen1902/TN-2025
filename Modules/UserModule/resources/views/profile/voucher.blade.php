<x-layouts.layout>
    <div class="container mx-auto max-w-[1200px] my-3 md:my-6 flex flex-col px-4 md:px-0">
        <div class="flex flex-col md:flex-row md:h-full w-full">
            <div class="w-full h-[500px] md:w-[300px] md:h-[500px] mb-4 md:mb-0">
                <x-usermodule::sidebar />
            </div>

            <div class="bg-white w-full md:w-[900px] py-4 md:py-8 rounded shadow-sm">
                <div class="mb-4 md:mb-6 flex justify-between items-center px-4">
                    <h1 class="text-xl md:text-3xl font-bold text-blue-600 tracking-wide">🎟 Ví Voucher</h1>
                </div>

                <hr class="border-t border-gray-300 my-2 md:my-4 mx-4">

                <div class="mb-4 border-b border-gray-200 relative">
                    <div class="flex overflow-x-auto scrollbar-hide" role="tablist">
                        <ul class="flex flex-nowrap whitespace-nowrap min-w-full">
                            @foreach($tabs as $id => $label)
                                <li class="mr-2" role="presentation">
                                    <button
                                        class="inline-block text-sm md:text-base font-medium px-4 py-2 border-b-4 {{ $loop->first ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-blue-500 hover:border-blue-300' }} transition-colors duration-300"
                                        id="{{ $id }}-tab"
                                        data-tabs-target="#{{ $id }}"
                                        type="button"
                                        role="tab"
                                        aria-controls="{{ $id }}"
                                        aria-selected="{{ $loop->first ? 'true' : 'false' }}">
                                        {{ $label }}
                                    </button>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>

                <div id="notificationTabContent" class="p-4">
                    @foreach($tabs as $id => $label)
                        <div class="tab-content {{ !$loop->first ? 'hidden' : '' }}" id="{{ $id }}">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 max-h-[calc(100vh-300px)] overflow-y-auto">
                                @foreach ($vouchers as $voucher)
                                    @if (
                                        $id === 'all' ||
                                        ($id === 'percent' && $voucher['category'] === 'Phần trăm') ||
                                        ($id === 'amount' && $voucher['category'] === 'Cố định')
                                    )
                                        <div class="bg-gradient-to-r from-blue-50 to-blue-100 border-l-4 border-blue-400 shadow-md rounded-xl flex overflow-hidden transition-transform duration-300">
    <div class="bg-blue-500 flex items-center justify-center px-3 text-white font-bold text-sm">
        <img src="{{ $voucher['image'] }}" class="w-10 h-10 object-contain" alt="Voucher icon">
    </div>

                                            <div class="flex-1 p-4">
                                                <div class="flex justify-between items-center mb-2">
                                                    <p class="text-base font-semibold text-gray-800">
                                                        🎁 {{ $voucher['discount'] }}
                                                    </p>
                                                    @if ($voucher['is_used'])
                                                        <span class="text-xs font-medium text-gray-500 bg-gray-200 px-2 py-1 rounded">Đã sử dụng</span>
                                                    @else
                                                        <button type="button" class="bg-blue-600 text-white text-xs px-3 py-1 rounded-lg hover:bg-blue-700" onclick="copyCode('{{ $voucher['code'] }}')">
                                                            Copy mã
                                                        </button>
                                                    @endif
                                                </div>
                                                <!-- Toast thông báo -->


                                                
                                                <p class="text-sm text-gray-700 mb-1">📄 <strong></strong> {{ $voucher['terms'] }}</p>
                                                
                                                <div class="flex items-center justify-between mt-2">
            <div class="flex items-center bg-gray-100 text-sm text-gray-700 px-3 py-1 rounded">
                <svg class="w-4 h-4 text-gray-500 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-4 4h6a2 2 0 012 2v6a2 2 0 01-2 2h-6a2 2 0 01-2-2v-6z" />
                </svg>
                {{ $voucher['code'] }}
                @if (!empty($voucher['quantity']) && $voucher['quantity'] > 1)
                    <span class="ml-1 text-xs text-gray-500">(x{{ $voucher['quantity'] }})</span>
                @endif
            </div>
            <p class="text-xs text-gray-500">⏳ HSD: {{ $voucher['expiry'] }}</p>
        </div>
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <script>
        
        document.addEventListener('DOMContentLoaded', () => {
            const tabButtons = document.querySelectorAll('[data-tabs-target]');
            const tabContents = document.querySelectorAll('#notificationTabContent > .tab-content');

            tabButtons.forEach(button => {
                button.addEventListener('click', () => {
                    const targetId = button.getAttribute('data-tabs-target').substring(1);

                    tabContents.forEach(content => content.classList.add('hidden'));

                    tabButtons.forEach(btn => {
                        btn.classList.remove('border-red-500', 'text-red-600');
                        btn.classList.add('border-transparent', 'text-gray-500');
                    });

                    const targetContent = document.getElementById(targetId);
                    if (targetContent) {
                        targetContent.classList.remove('hidden');
                    }

                    button.classList.add('border-red-500', 'text-red-600');
                    button.classList.remove('border-transparent', 'text-gray-500');
                });
            });
        });
        function copyCode(code) {
    navigator.clipboard.writeText(code).then(() => {
        showToast("Đã sao chép mã: " + code);
    }).catch(err => {
        console.error('Không thể sao chép mã: ', err);
    });
}

function showToast(message) {
    const toast = document.getElementById("toast");
    toast.textContent = message;
    toast.classList.remove("opacity-0");
    toast.classList.add("opacity-100");

    setTimeout(() => {
        toast.classList.remove("opacity-100");
        toast.classList.add("opacity-0");
    }, 2000);
}

    </script>

    <script>
        console.log("Dữ liệu vouchers truyền vào view:");
        console.log(@json($vouchers));
    </script>
<div id="toast"
     class="fixed bottom-6 right-6 bg-green-500 text-white text-sm font-medium px-4 py-2 rounded shadow-lg opacity-0 transition-opacity duration-300 z-50">
</div>
</x-layouts.layout>
