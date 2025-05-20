<x-layouts.layout>
    <div class="container mx-auto max-w-[1200px] my-3 md:my-6 flex flex-col px-4 md:px-0">
        <div class="flex flex-col md:flex-row md:h-full w-full">
            <div class="w-full h-[500px] md:w-[300px] md:h-[500px] mb-4 md:mb-0">
                <x-usermodule::sidebar />
            </div>

            <div class="bg-white w-full md:w-[900px] py-4 md:py-8 rounded shadow-sm">
                <div class="mb-4 md:mb-6 flex justify-between items-center px-4">
                    <h1 class="text-lg md:text-2xl font-medium">Ví Voucher</h1>
                </div>

                <hr class="border-t border-gray-300 my-2 md:my-4 mx-4">

                <div class="mb-4 border-b border-gray-200 relative">
                    <div class="flex overflow-x-auto scrollbar-hide" role="tablist">
                        <ul class="flex flex-nowrap whitespace-nowrap min-w-full">
                            @foreach($tabs as $id => $label)
                                <li class="mr-2" role="presentation">
                                    <button
                                        class="inline-block text-gray-500 p-4 border-b-2 border-transparent {{ $loop->first ? 'border-red-500 text-red-500' : 'hover:text-gray-600 hover:border-gray-300' }} rounded-t-lg"
                                        id="{{ $id }}-tab" data-tabs-target="#{{ $id }}" type="button" role="tab"
                                        aria-controls="{{ $id }}" aria-selected="{{ $loop->first ? 'true' : 'false' }}">
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
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 max-h-[calc(100vh-300px)] overflow-y-auto">
                                @foreach ($vouchers as $voucher)
                                    @if ($id == 'percent' && $voucher['category'] == 'Phần trăm' || $id == 'amount' && $voucher['category'] == 'Cố định')
                                        <div class="bg-white border border-gray-200 rounded-lg shadow-sm flex">
                                            <div class="bg-green-500" style="width: 85px; border-top-left-radius: 4px; border-bottom-left-radius: 4px; display: flex; align-items: center; justify-content: center;">
                                                <img src="{{ $voucher['image'] }}" style="width: 80px; height: 40px;" alt="Voucher icon">
                                            </div>

                                            <div class="flex-1 p-4">
                                                <div class="flex justify-between items-start mb-2">
                                                    <p class="text-sm font-bold text-gray-800">
                                                        {{ $voucher['discount'] }}
                                                    </p>
                                                    <button type="button" class="text-xs text-blue-600 hover:underline" data-modal-id="voucher-modal-{{ $voucher['id'] }}">Chi tiết</button>
                                                </div>
                                                <p class="text-xs text-gray-800 mb-2">
                                                    {{ $voucher['condition'] }}
                                                </p>
                                                <div class="flex items-center mb-2">
                                                    <div class="flex items-center mb-2" style="background-color: #E3E5E5; border-radius: 4px; padding: 8px 12px;">
                                                        <svg class="w-4 h-4 text-gray-500 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-4 4h6a2 2 0 012 2v6a2 2 0 01-2 2h-6a2 2 0 01-2-2v-6z" />
                                                        </svg>
                                                        <p class="text-sm font-medium">{{ $voucher['code'] }}</p>
                                                    </div>
                                                </div>

                                                <div class="flex justify-between items-center">
                                                    <p class="text-xs text-gray-500">
                                                        HSD: {{ $voucher['expiry'] }}
                                                    </p>
                                                    @if ($voucher['is_used'])
                                                        <span class="text-xs text-gray-500 font-medium">Đã sử dụng</span>
                                                    @else
                                                        <button type="button" class="bg-blue-500 text-white text-xs font-medium px-3 py-1 rounded-lg hover:bg-blue-600" onclick="copyCode('{{ $voucher['code'] }}')">
                                                            Copy mã
                                                        </button>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Modal chi tiết -->
                                        <div id="voucher-modal-{{ $voucher['id'] }}" class="fixed inset-0 flex items-center justify-center z-50 hidden">
                                            <div class="fixed inset-0" style="background-color: #261E1E; opacity: 0.5;" data-modal-backdrop="voucher-modal-{{ $voucher['id'] }}"></div>
                                            <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-md relative">
                                                <button type="button" class="absolute top-2 right-2 text-gray-500 hover:text-gray-700" data-modal-close="voucher-modal-{{ $voucher['id'] }}">
                                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                    </svg>
                                                </button>
                                                <h2 class="text-lg font-bold text-gray-800 mb-4">Điều kiện áp dụng</h2>
                                                <div class="p-4 mb-4" style="background-color: #ffdc73; border-radius: 4px;">
                                                    <p class="text-sm text-gray-800"><strong>Mã giảm giá:</strong> {{ $voucher['discount'] }}</p>
                                                    <p class="text-sm text-gray-800">{{ $voucher['terms'] }}</p>
                                                </div>
                                                <div class="flex justify-between items-center">
                                                    <p class="text-xs text-gray-500">HSD: {{ $voucher['expiry'] }}</p>
                                                    @if ($voucher['is_used'])
                                                        <span class="text-xs text-gray-500 font-medium">Đã sử dụng</span>
                                                    @else
                                                        <button type="button" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600" onclick="copyCode('{{ $voucher['code'] }}')">
                                                            Copy mã
                                                        </button>
                                                    @endif
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
                        btn.classList.remove('border-red-500', 'text-red-500');
                        btn.classList.add('border-transparent', 'hover:text-gray-600', 'hover:border-gray-300');
                    });

                    const targetContent = document.getElementById(targetId);
                    if (targetContent) {
                        targetContent.classList.remove('hidden');
                    }

                    button.classList.add('border-red-500', 'text-red-500');
                    button.classList.remove('border-transparent', 'hover:text-gray-600', 'hover:border-gray-300');
                });
            });

            const modalButtons = document.querySelectorAll('[data-modal-id]');
            const modalCloses = document.querySelectorAll('[data-modal-close]');
            const modalBackdrops = document.querySelectorAll('[data-modal-backdrop]');

            modalButtons.forEach(button => {
                button.addEventListener('click', () => {
                    const modalId = button.getAttribute('data-modal-id');
                    const modal = document.getElementById(modalId);
                    if (modal) {
                        modal.classList.remove('hidden');
                    }
                });
            });

            modalCloses.forEach(button => {
                button.addEventListener('click', () => {
                    const modalId = button.getAttribute('data-modal-close');
                    const modal = document.getElementById(modalId);
                    if (modal) {
                        modal.classList.add('hidden');
                    }
                });
            });

            modalBackdrops.forEach(backdrop => {
                backdrop.addEventListener('click', () => {
                    const modalId = backdrop.getAttribute('data-modal-backdrop');
                    const modal = document.getElementById(modalId);
                    if (modal) {
                        modal.classList.add('hidden');
                    }
                });
            });
        });

        function copyCode(code) {
            navigator.clipboard.writeText(code).then(() => {
                alert('Mã ' + code + ' đã được sao chép!');
            }).catch(err => {
                console.error('Không thể sao chép mã: ', err);
            });
        }
    </script>
</x-layouts.layout>