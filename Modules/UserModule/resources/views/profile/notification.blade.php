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
                    <h1 class="text-lg md:text-2xl font-medium">Thông báo</h1>
                </div>
                <hr class="border-t border-gray-300 my-2 md:my-4 mx-4">

                {{-- Tabs --}}
                <div class="mb-4 border-b border-gray-200 relative">
                    <div class="flex overflow-x-auto scrollbar-hide" role="tablist">
                        <ul class="flex flex-nowrap whitespace-nowrap min-w-full">
                            @foreach($tabs as $id => $label)
                                <li class="mr-2" role="presentation">
                                    <a href="{{ route('notification.index', ['tab' => $id]) }}"
                                        class="inline-block p-4 border-b-2 {{ $selectedTab == $id ? 'border-red-500 text-red-500' : 'border-transparent text-gray-500 hover:text-gray-600 hover:border-gray-300' }}"
                                        role="tab" aria-selected="{{ $selectedTab == $id ? 'true' : 'false' }}">
                                        {{ $label }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>

                {{-- Notifications list --}}
                <div class="p-4">
                    @forelse ($notifications as $notification)
                        <div class="bg-white border border-gray-200 rounded-lg p-4 shadow-sm mb-4">
                            <div class="flex items-center mb-3">
                                @php $category = $notification->notification_type; @endphp
                                @if ($category == 'Đơn hàng')
                                    <div
                                        class="w-8 h-8 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center mr-2">
                                        <x-heroicon-o-shopping-bag class="w-5 h-5" />
                                    </div>
                                @elseif ($category == 'Tài khoản')
                                    <div
                                        class="w-8 h-8 bg-green-100 text-green-600 rounded-full flex items-center justify-center mr-2">
                                        <x-heroicon-o-user-circle class="w-5 h-5" />
                                    </div>
                                @elseif ($category == 'Khuyến mãi')
                                    <div
                                        class="w-8 h-8 bg-yellow-100 text-yellow-600 rounded-full flex items-center justify-center mr-2">
                                        <x-heroicon-o-gift class="w-5 h-5" />
                                    </div>
                                @elseif ($category == 'Ưu đãi độc quyền')
                                    <div
                                        class="w-8 h-8 bg-purple-100 text-purple-600 rounded-full flex items-center justify-center mr-2">
                                        <x-heroicon-o-star class="w-5 h-5" />
                                    </div>
                                @endif

                                <h2 class="text-sm font-semibold text-gray-800 uppercase">
                                    {{ $category }}
                                </h2>
                            </div>

                            <hr class="border-gray-200 mb-3">

                            <div class="flex items-start space-x-4">
                                <div class="flex-shrink-0">
                                    <img class="h-16 w-16 object-contain"
                                        src="{{ $notification->thumbnail ? asset('storage/' . $notification->thumbnail) : asset('storage/thumbnails/default.jpg') }}"
                                        alt="Thông báo">
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-gray-800 mb-1">{{ $notification->name }}</p>
                                    <p class="text-sm font-medium text-gray-800 mb-1">{{ $notification->content }}</p>
<p class="text-xs text-gray-500">
    {{ \Carbon\Carbon::parse($notification->created_at ?? $notification->time)->diffForHumans() }}</p>
                                </div>
                            </div>
                        </div>
                    @empty
                        <p class="text-center text-gray-500">Không có thông báo nào.</p>
                    @endforelse
                    <div class="mt-6 px-4 bg-white">
                        {{ $notifications->appends(['tab' => $selectedTab])->links('pagination::tailwind') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.layout>